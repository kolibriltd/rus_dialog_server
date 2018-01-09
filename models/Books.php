<?php

/**
 * This is the model class for table "tbl_books".
 *
 * The followings are the available columns in table 'tbl_books':
 * @property integer $id_book
 * @property string $name
 * @property string $author
 * @property string $path_txt_file
 * @property string $path_cover_file
 * @property string $description
 * @property integer $is_view_count
 * @property integer $guest
 * @property string $raiting
 * @property integer $raiting_count
 * @property float $raiting_sum
 * @property integer $type_id
 * @property string $created
 * @property string $last_modified
 * @property integer $status
 * @property integer $user_id
 * @property string $dir_book
 */
class Books extends CActiveRecord
{
	public $txt_file;
        public $cover_file;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Books the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_books';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, author, description, last_modified', 'required'),
			array('id_book, is_view_count, guest, raiting_count, status, user_id', 'numerical', 'integerOnly'=>true),
			array('name, author, path_cover_file, last_modified, created, dir_book', 'length', 'max'=>100),
			/*array('txt_file', 'file', 'types'=>'txt'),
			array('path_cover_file', 'file', 'types'=>'jpg, gif, png'),*/
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_book, name, author, status', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
            return array(
                'id_book' => 'Id Book',
                'name' => 'Name',
                'author' => 'Author',
                'path_txt_file' => 'Path Txt File',
                'path_cover_file' => 'Path Cover File',
                'description' => 'Description'
            );
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id_book',$this->id_book);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('author',$this->author,true);
		$criteria->compare('status',$this->status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
