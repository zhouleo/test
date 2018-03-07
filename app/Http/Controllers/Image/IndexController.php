<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 2018/3/6
 * Time: 20:12
 */
namespace App\Http\Controllers\Image;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use App\Libs;

class IndexController extends Controller
{
    protected   $imageModel;
    public function __construct(Request $request)
    {
        $this->imageModel    = new Image();
    }

    /**
     * 获取图片地址,并生成图片
     * @param Request $request
     * @return mixed
     */
    public function actionIndex(Request $request)
    {
        $imageUrl = $request->input('img');
        $imageArray = explode('-',$imageUrl); //处理获取的url,“-”左边为ID，右边为宽或高以及类型
        $imageId = $imageArray[0];
        //查询获取图片数据
        $imageData = $this->imageModel->getImage($imageId);
        $imageWidth = $imageData['width'];
        $imageHeigh = $imageData['height'];
        $imageInfoArray = explode('.',$imageArray[1]); //截取高度或宽度以及类型
        $imageSize = $imageInfoArray[0];
        $size = substr($imageSize,1);
        $type = strtolower(substr($this->size,0,1));//获取图片是取高还是宽
        $imageType = $imageInfoArray[1];
        $image = new Libs\ImageCreate($imageData['url'],$imageWidth,$imageHeigh,$imageType,$type,$size);
        $imagePath = $image->showImage();
        if(strpos($_SERVER["HTTP_USER_AGENT"],"Chrome")){
            $imageType = 'webp';
        }
        header("Content-Type:image/".$imageType);
        echo file_get_contents($imagePath.$imageType);
        exit;
    }

}