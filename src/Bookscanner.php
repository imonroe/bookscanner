<?php
namespace imonroe\bookscanner;
use Exception;

class Bookscanner extends Model
{

	static function get_isbn_data($isbn_string){
		$sanitized_isbn = self::test_barcode($isbn_string);
		$url = 'http://openlibrary.org/api/books?bibkeys=isbn:'.$sanitized_isbn.'&jscmd=details&format=json';
		$curl = curl_init();
		curl_setopt ($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($curl, CURLOPT_CONNECTTIMEOUT, 0);
		$data = curl_exec($curl);
		curl_close ($curl);
		$isbn_data = json_decode($data, true);
		$output = self::parse_isbn_data($sanitized_isbn, $isbn_data);
		return $output;
	}

	static function test_barcode($barcode_string){
		// sanitize a little.
		$barcode_string = trim($barcode_string);
		$barcode_string = str_replace('-', '', $barcode_string);
		$digit_count = strlen($barcode_string);

		if ( self::findIsbn( $barcode_string ) ) {
			return $barcode_string;
		} else {
			// not valid
			$message = 'THERE IS SOME KIND OF PROBLEM WITH THAT ISBN NUMBER';
			throw new Exception($message);
		}
	}

	static function parse_isbn_data($sanitized_isbn, $isbn_array){
		/*
			returns an array like:
			array(
				'isbn' => ,
				'info_url' => ,
				'title' => ,
				'subjects' => ,
				'publisher' => ,
				'author' => ,
				'publication_date' => ,
				'number_of_pages' => ,
				'physical_format' => ,
				'thumbnail_url' => ,
			)
		*/
		$output = array();
		$output['isbn'] = $sanitized_isbn;
		$item = array_pop($isbn_array);
		if (!empty($item)){
			$output['info_url'] = $item['info_url'];
			$output['title'] = ucwords($item['details']['title']);
			if (!empty($item['details']['subjects'])){
				$output['subjects'] = implode(', ', $item['details']['subjects']);
			}
			if (!empty($item['details']['publishers'])){
				$output['publisher'] = implode(', ', $item['details']['publishers']);
			}
			if (!empty($item['details']['authors'])){
				$output['author'] = '';
				foreach($item['details']['authors'] as $author){
					$output['author'] .= $author['name'] . ', ';
				}
				if (strlen($output['author']) > 2 ){
					$output['author'] = substr($output['author'], 0, -2);
				}
			}
			if (!empty($item['details']['publish_date'])){
				$output['publication_date'] = $item['details']['publish_date'];
			}

			if (!empty($item['details']['number_of_pages'])){
				$output['number_of_pages'] = $item['details']['number_of_pages'];
			}

			if (!empty($item['details']['physical_format'])){
				$output['physical_format'] = $item['details']['physical_format'];
			}

			if (!empty($item['thumbnail_url'])){
				$output['thumbnail_url'] = $item['thumbnail_url'];
			}
		}
		return $output;
	}

	/*
		Validation functions below based on:
		https://stackoverflow.com/questions/14095778/regex-differentiating-between-isbn-10-and-isbn-13
	*/
	static function findIsbn($str){
	    $regex = '/\b(?:ISBN(?:: ?| ))?((?:97[89])?\d{9}[\dx])\b/i';
	    if (preg_match($regex, str_replace('-', '', $str), $matches)) {
	        return (10 === strlen($matches[1]))
	            ? self::isValidIsbn10($matches[1])   // ISBN-10
	            : self::isValidIsbn13($matches[1]);  // ISBN-13
	    }
	    return false; // No valid ISBN found
	}

	static function isValidIsbn10($isbn){
	    $check = 0;
	    for ($i = 0; $i < 10; $i++) {
	        if ('x' === strtolower($isbn[$i])) {
	            $check += 10 * (10 - $i);
	        } elseif (is_numeric($isbn[$i])) {
	            $check += (int)$isbn[$i] * (10 - $i);
	        } else {
	            return false;
	        }
	    }
	    return (0 === ($check % 11)) ? 1 : false;
	}

	static function isValidIsbn13($isbn){
	    $check = 0;
	    for ($i = 0; $i < 13; $i += 2) {
	        $check += (int)$isbn[$i];
	    }
	    for ($i = 1; $i < 12; $i += 2) {
	        $check += 3 * $isbn[$i];
	    }
	    return (0 === ($check % 10)) ? 2 : false;
	}
}
