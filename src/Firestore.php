<?php


namespace Syailendra\Firebase;


use Google\Cloud\Firestore\FieldValue;

class Firestore
{
    private static $statement = null;

    /**
     * Firestore constructor.
     */
    public function __construct()
    {

    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|mixed
     */
    private static function getConnection()
    {
        return app('firebase.firestore');
    }

    /**
     * @param string $collection
     * @return Firestore |null
     */
    public static function collection(string $collection)
    {
        self::$statement = self::getConnection()->database()->collection($collection);
        return new self;
    }


    /**
     * @param array $data
     * @return mixed
     */
    public function insert(array $data)
    {
        $doc = self::$statement->newDocument();
        $doc->set($data);
        return $doc->id();
    }

    /**
     * @param $document_id
     * @return $this
     */
    public function whereDoc($document_id)
    {
        self::$statement = self::$statement->document($document_id);
        return $this;
    }

    /**
     * @param $data
     * @return null
     */
    public function update($data)
    {
        $update = [];
        foreach ($data as $index => $value) {
            $row = ['path' => $index, 'value' => $value];
            $update[] = $row;
        }

        self::$statement = self::$statement->update($update);
        return self::$statement;

    }

    /**
     * @return null
     */
    public function deleteDoc()
    {
        self::$statement = self::$statement->delete();
        return self::$statement;
    }

    /**
     * @param array|string $fields
     * @return $this
     */
    public function deleteFields($fields)
    {
        if(is_array($fields)){
            $data = [];
            foreach ($fields as $field) {
                $data[] = ["path" => $field,"value" => FieldValue::deleteField()];
            }
            self::$statement = self::$statement->update($data);

        }else{
            self::$statement = self::$statement->update([
                ["path" => $fields,'value' => FieldValue::deleteField()]
            ]);
        }

        return $this;
    }

    /**
     * @param $field
     * @param null $operator
     * @param null $value
     * @return $this
     */
    public function where($field, $operator = null, $value = null)
    {
        if (is_array($field)) {
            foreach ($field as $item) {
                if (isset($item[2])) {
                    self::$statement = self::$statement->where($item[0], $item[1], $item[2]);
                } else {
                    $item[2] = $item[1];
                    $item[1] = '=';
                    self::$statement = self::$statement->where($item[0], $item[1], $item[2]);
                }
            }
        } else {
            if ($value !== null) {
                self::$statement = self::$statement->where($field, $operator, $value);
            } else {
                $value = $operator;
                $operator = "=";
                self::$statement = self::$statement->where($field, $operator, $value);
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function get()
    {

        return self::$statement->documents();
    }

    /**
     * To be develop (conflict with function before get)
     * @return array|mixed
     */
    public function first()
    {
        $docs = self::$statement;
        $data = [];

        foreach ($docs as $doc) {
            $data = $doc;
            break;
        }
        return $data;
    }

    /**
     * @return mixed
     */
    public function snapshot()
    {
        return self::$statement->snapshot();
    }

    /**
     * @return mixed
     */
    public function getCollections()
    {
        return self::$statement->collections();
    }

    /**
     * @param $field
     * @param null $order_type
     * @return $this
     */
    public function orderBy($field, $order_type = null)
    {
        //value case sensitive
        if (is_array($field)) {
            foreach ($field as $item) {
                if (isset($item[1])) {
                    self::$statement = self::$statement->orderBy($item[0], $item[1]);
                } else {
                    self::$statement = self::$statement->orderBy($item[0]);
                }
            }
        } else {
            if ($order_type === null) {
                self::$statement = self::$statement->orderBy($field);
            } else {
                self::$statement = self::$statement->orderBy($field, $order_type);
            }
        }

        return $this;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit)
    {
        self::$statement = self::$statement->limit($limit);

        return $this;
    }
}
