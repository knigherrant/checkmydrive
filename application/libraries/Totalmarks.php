<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Teacher Attendance Class
 *
 *
 * @package			CodeIgniter
 * @subpackage		Libraries
 * @category		Libraries
 * @author			Waiyan
 * @link			http://www.facebook.com/sheinwaiyanlin
 */
class Totalmarks
{


	public function total($exam_id,$student_id)
	{
$CI =& get_instance();
$student=$CI->db->get('student')->result();
foreach($student as $stu){
$id=$stu->student_user_id;
$CI->db->where('exam_id',$exam_id); 
$CI->db->where('student_id',$id); 
$total=$CI->db->get('marks')->result();
foreach($total as $t){
echo $t->mark;
}
}
	}
			
}
// END Template class