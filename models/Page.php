<?php

namespace app\models;

use app\traits\UseTvText;
use Yii;
use yii\base\ErrorException;

/**
 * This is the model class for table "page".
 *
 * @property integer $id
 * @property string $alias
 * @property string $template
 * @property string $lang
 * @property string $title
 * @property string $h1
 * @property string $description
 * @property string $keywords
 * @property string $text
 * @property integer $status
 * @property string $created
 * @property string $updated
 */
class Page extends \yii\db\ActiveRecord
{
    use UseTvText;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'page';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['alias', 'template', 'title', 'description', 'keywords', 'text', 'created', 'updated'], 'required'],

            [['title'], 'required'],
            [['status', 'created', 'updated'], 'default', 'value' => 0],

            [['phone', 'address'], 'safe'], // Виртуальные атрибуты из таблицы tv_text

            [['text'], 'string'],
            [['status'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['alias', 'title', 'h1', 'description', 'keywords'], 'string', 'max' => 255],
            [['template'], 'string', 'max' => 11],
            [['lang'], 'string', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'alias' => 'Alias',
            'template' => 'Template',
            'lang' => 'Lang',
            'title' => 'Title',
            'h1' => 'H1',
            'description' => 'Description',
            'keywords' => 'Keywords',
            'text' => 'Text',
            'status' => 'Status',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }

    /**
     * @inheritdoc
     * @return PageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PageQuery(get_called_class());
    }

    public function beforeSave($insert)
    {
        Yii::warning($this->attributes);
        Yii::warning($this->_tvTexts);
        return parent::beforeSave($insert);
    }

}
