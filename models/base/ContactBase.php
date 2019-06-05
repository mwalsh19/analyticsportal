<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "{{%contact}}".
 *
 * @property integer $id_contact
 * @property string $first_name
 * @property string $last_name
 * @property string $title
 * @property string $email
 * @property string $phone
 * @property string $ext
 * @property string $mobile
 * @property string $notes
 * @property string $time_zone
 * @property string $photo
 * @property string $create_date
 * @property integer $tbl_media_publisher_id_media_publisher
 *
 * @property MediaPublisher $tblMediaPublisherIdMediaPublisher
 */
class ContactBase extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%contact}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['first_name', 'last_name', 'email', 'tbl_media_publisher_id_media_publisher'], 'required'],
            [['notes'], 'string'],
            [['create_date'], 'safe'],
            [['tbl_media_publisher_id_media_publisher'], 'integer'],
            [['first_name', 'last_name'], 'string', 'max' => 50],
            [['title', 'email'], 'string', 'max' => 100],
            [['phone', 'mobile'], 'string', 'max' => 20],
            [['ext'], 'string', 'max' => 10],
            [['time_zone'], 'string', 'max' => 5],
            [['photo'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id_contact' => 'Id Contact',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'title' => 'Title',
            'email' => 'Email',
            'phone' => 'Phone',
            'ext' => 'Ext',
            'mobile' => 'Mobile',
            'notes' => 'Notes',
            'time_zone' => 'Time Zone',
            'photo' => 'Photo',
            'create_date' => 'Create Date',
            'tbl_media_publisher_id_media_publisher' => 'Tbl Media Publisher Id Media Publisher',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMediaPublisherIdMediaPublisher() {
        return $this->hasOne(MediaPublisherBase::className(), ['id_media_publisher' => 'tbl_media_publisher_id_media_publisher']);
    }

}
