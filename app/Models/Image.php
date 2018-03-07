<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 2018/3/6
 * Time: 20:16
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model as ModelBase;

class Image extends ModelBase
{
    //图片模型
    protected $table = 'images';
    protected $fillable = [
        'image_id',
        'storage',
        'image_name',
        'ident',
        'url',
        'l_ident',
        'l_url',
        'm_ident',
        'm_url',
        's_ident',
        's_url',
        'width',
        'height',
        'watermark',
        'last_modified'
    ];

    /**
     * 字段要转换的类型
     * @var array
     */
    protected $casts = [
        'width' => 'integer',
        'height' => 'integer',
        'last_modified' => 'integer'
    ];
    /**
     * 获取图片
     * @param $imageId
     */
    public function getImage($imageId)
    {
        $where = [];
        $imageId && $where[] = ['image_id', $imageId];
        $image = $this->where($where)->first();
        return $image ? $image->toArray() : [];//返回数组格式
    }

    /**
     * 插入图片
     * @param $data
     * @return bool
     */
    public function insertData($data){
        //图片信息
        $image = array_only($data, [
            'image_id',
            'storage',
            'image_name',
            'ident',
            'url',
            'l_ident',
            'l_url',
            'm_ident',
            'm_url',
            's_ident',
            's_url',
            'width',
            'height',
            'watermark',
        ]);
        $goods['last_modified'] = time();
        if ( $this->insertGetId($image)) {
            return true;
        }
        return false;
    }

    /**
     * 更新图片
     * @param $data
     * @return bool
     */
    public function updateData($data){
        //图片信息
        $image = array_only($data, [
            'storage',
            'image_name',
            'ident',
            'url',
            'l_ident',
            'l_url',
            'm_ident',
            'm_url',
            's_ident',
            's_url',
            'width',
            'height',
            'watermark',
        ]);
        $goods['last_modified'] = time();
        $goodsId = $this->where(['id' => $data['image_id']])->update($image);
        if ($goodsId>0) {
            return true;
        }
        return false;
    }


}