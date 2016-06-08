<?php
/* This code creates reversed copy of the source file */
define('BUFF_SIZE', '4096');

$source_file_name = 'abc.txt';
$free_bytes_size = disk_free_space('./');

function reverse_buffer($str) {
	$str_len = strlen($str);
	for($i=0; $i<$str_len/2; $i++) {
		$t = $str[$i];
		$str[$i] = $str[$str_len-1-$i];
		$str[$str_len-1-$i] = $t;
	}
	return $str;
}

if(is_readable($source_file_name)) {
	$src_h = fopen($source_file_name, 'r');
	if($src_h) {
		$src_data = fstat($src_h);
		if ($free_bytes_size > $src_data['size']) {
			list($target_file_name,$target_file_ext) = explode('.',$source_file_name);
			$target_file_name = reverse_buffer($target_file_name).'.'.$target_file_ext;
			
			$trg_h = fopen($target_file_name,'w');
			
			$read_pointer = $src_data['size'];
			$buff_str = '';
			
			while ($read_pointer>0) {
				$read_pointer = $read_pointer - BUFF_SIZE;
				if($read_pointer < 0) $read_pointer = 0;
				
				fseek($src_h, $read_pointer);
				$buff_str = fread($src_h, BUFF_SIZE);
				$buff_str = reverse_buffer($buff_str);
				
				if(!fwrite($trg_h, $buff_str, BUFF_SIZE)) {
					exit('Wrong record in step '.$read_pointer);
				}
			}
			fclose($trg_h);
		}
		fclose($src_h);
		echo 'Operation is succ: '.$target_file_name;
	}
} else exit($source_file_name.' doesn\'t exists');

?>
