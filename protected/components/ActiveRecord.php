<?php
namespace application\components;

/**
 * Class ActiveRecord Base class for all active records
 */
abstract class ActiveRecord extends \CActiveRecord
{
    /**
     * @var bool Не использовать физическое удаление записей, а проставлять Delete = true
     */
    protected $useSoftDelete = false;

    /**
     * @var array Sort params by default
     */
    protected $defaultOrderBy = ['"t"."Id"' => SORT_ASC];

    /**
     * Returns name of the class
     * @return string
     */
    public static function className()
    {
        return get_called_class();
    }

    /**
     * Returns the instance of the active record by using late static binding
     * @param string $className
     * @return static
     */
    public static function model($className = null)
    {
        if (!$className) {
            $className = get_called_class();
        }

        return parent::model($className);
    }

    /**
     * Creates a new one moel
     * @param array $attributes
     * @return mixed
     */
    public static function insertOne($attributes = [])
    {
        $model = new static();
        $model->setAttributes($attributes, false);

        return $model->save();
    }

    /**
     * Находит одну запись по PrimaryKey
     * @param mixed $pk
     * @return array|\CActiveRecord|mixed|null
     */
    public static function findOne($pk)
    {
        return static::model()->findByPk($pk);
    }

    /**
     * Magic method __call
     * @param string $name
     * @param array $parameters
     * @return $this|mixed
     */
    public function __call($name, $parameters)
    {
        if (strpos($name, 'by') === 0) {
            $column = substr($name, 2);
            $schema = $this->getTableSchema();
            if (array_key_exists($column, $schema->columns)) {
                $criteria = new \CDbCriteria();
                if ($schema->getColumn($column)->dbType !== 'boolean') {
                    $value = $parameters[0];
                    if ($value) {
                        if (is_array($value)) {
                            $criteria->addInCondition('"t"."' . $column . '"', $value);
                        } else {
                            $criteria->addCondition('"t"."' . $column . '" = :'.$column);
                            $criteria->params[$column] = $value;
                        }
                    } else {
                        $criteria->addCondition('"t"."' . $column . '" IS NULL');
                    }
                } else {
                    $criteria->addCondition(($parameters[0] === false ? 'NOT ' : '') . '"t"."' . $column . '"');
                }
                $this->getDbCriteria()->mergeWith($criteria, true);

                return $this;
            }
        }

        return parent::__call($name, $parameters);
    }

    /**
     * Set sort orders
     * @param array $orders
     * @return $this
     */
    public function orderBy($orders)
    {
        if (!is_array($orders)) {
            $orders = [$orders];
        }

        $criteria = new \CDbCriteria();
        foreach ($orders as $column => $order) {
            if (!is_string($column)) {
                $column = $order;
                $order  = SORT_ASC;
            }
            $criteria->order .= (!empty($criteria->order) ? ', ' : '') . $column . ' ' . ($order === SORT_DESC ? 'DESC' : 'ASC');
        }
        $this->getDbCriteria()->mergeWith($criteria);

        return $this;
    }

    /**
     * Отсортировать записи, используя сортировку по умолчанию
     * @see [$this->defaultOrderBy]
     * @return ActiveRecord
     */
    public function ordered()
    {
        return $this->orderBy($this->defaultOrderBy);
    }

    /**
     * Устанавливает лимит записей
     * @param int $limit
     * @return $this
     */
    public function limit($limit)
    {
        $this->getDbCriteria()->limit = $limit;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function delete()
    {
        if ($this->useSoftDelete) {
            $this->Deleted = true;
            $this->DeletionTime = date('Y-m-d H:i:s');
            $this->save();
            return true;
        } else {
            return parent::delete();
        }
    }
}
