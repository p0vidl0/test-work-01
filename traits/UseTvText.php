<?php
namespace app\traits;

use app\models\TvText;
use Yii;
use yii\base\ErrorException;

trait UseTvText
{
    private $_tvTexts = [];

    public function __get($name)
    {
        try {
            return parent::__get($name);
        } catch (\yii\base\UnknownPropertyException $e) {
            if (array_key_exists($name, $this->_tvTexts)) {
                return $this->_tvTexts[$name];
            } else if ($tvText = TvText::findOne([
                'model' => $this->className(),
                'model_id' => $this->id,
                'name' => $name,
            ])) {
                $this->_tvTexts[$name] = $tvText->content;
                return $this->_tvTexts[$name];
            }
            return false;
        }
    }

    public function __set($name, $value)
    {
        try {
            parent::__set($name, $value);
        } catch (\yii\base\UnknownPropertyException $e) {
            $this->_tvTexts[$name] = $value;
        }
    }

    public function getTvTexts()
    {
        return $this->hasMany(TvText::className(), ['model' => $this->className()])
            ->andWhere(['model_id' => $this->id]);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        foreach ($this->_tvTexts as $name => $content) {
            if ($tvText = TvText::findOne([
                'model' => $this->className(),
                'model_id' => $this->id,
                'name' => $name,
            ])) {
                $tvText->content = $content;
            } else {
                $tvText = new TvText([
                    'model' => $this->className(),
                    'model_id' => $this->id,
                    'name' => $name,
                    'content' => $content,
                ]);
            }
            if (!$tvText->save()) {
                throw new ErrorException('Не удалось сохранить дополнительные данные для модели.');
//                Yii::error('Не удалось сохранить дополнительные данные для модели.');
//                return false;
            }
        }
    }

    public function beforeDelete()
    {
        if (is_array($this->tvTexts)) {
            foreach ($this->tvTexts as $tvText) {
                $tvText->delete();
            }
        }

        return parent::beforeDelete();
    }

}