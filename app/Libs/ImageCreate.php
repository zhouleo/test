<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 2018/3/6
 * Time: 20:20
 */
namespace App\Libs;

use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine;

class ImageCreate
{
    private $imageId; //图片ID唯一
    private $src;  //图片路径
    private $width; //原始宽
    private $height; //原始高
    private $fileType; //文件类型（jpg/png/webp）
    private $type = 'w'; //图片去高或宽
    private $size;  //图片高或宽的尺寸
    private $thumbWidth;//比例宽
    private $thumbHeight;//比例高
    private $thumbPath = './public/image/';//小图存储路径

    public function __construct($src, $width, $height, $fileType,$type='w',$size)
    {
        $this->src = $src;
        $this->width = $width;
        $this->height = $height;
        $this->fileType = $fileType;
        $this->type = $type;
        $this->size = $size;
        //重新计算宽和高
        if( $this->type =='w'){
            $this->thumbWidth = $this->size;
            $this->thumbHeight = floor($this->height * $this->size / $this->width ); //按比例生成高度
            if($this->size >= $this->width){
                $this->thumbWidth = $this->width;
                $this->thumbHeight = $this->height;
            }
        }else{
            $this->thumbWidth = floor($this->width * $this->size / $this->height ); //按比例生成宽度
            $this->thumbHeight = $this->size;
            if($this->size >= $this->height){
                $this->thumbWidth = $this->width;
                $this->thumbHeight = $this->height;
            }
        }
    }

    /**
     * 读取图片，如没有则
     * 生成3种图片各10张
     *
     */
    public function showImage()
    {
        $keyNum = round($this->width / 10); //与10整除取整数位
        $flag = 0; //记录最接近的最大尺寸大小
        for($i = 1;$i <= 10;$i++){ //生成原始大小一张以及10次不同宽度的图片
            $key = $this->width - $keyNum * $i;  //原始宽度与倍数相减10次
            if($key > 0){
                $newWidth[$i] = $key;
            }else{
                $newWidth[$i] = $keyNum / 2;
            }
            if($key >= $this->thumbWidth && $this->thumbWidth < $this->width){ //取上一个最大尺寸
                $flag = $key;
            }
        }
        if(file_exsits($this->thumbPath.$this->imageId.'-'. $flag.'.webp')){ //判断是否有生成过图片
            return $this->thumbPath.$this->imageId.'-'.$flag;
        }
        foreach($newWidth  as $value){
            $newHeight = floor($this->height * $value / $this->width ); //按比例生成高度
            $this->createImage($value,$newHeight,'png');
            $this->createImage($value,$newHeight,'jpg');
            $this->createImage($value,$newHeight,'webp');
        }
        return $this->thumbPath.$this->imageId.'-'.$flag;
    }

    /**
     * 生成图片函数
     *
     */
    private function createImage($newWidth,$newHeight,$extension)
    {
        $imagine = new Imagine();
        $imagine->open($this->src)
            ->resize(new Box($newWidth, $newHeight))
            ->save($this->thumbPath.$this->imageId.'-'.$newWidth.'.'.$extension, array('flatten' => false)); //使用官网方法创建图片
        return true;
    }

}