<?php

abstract class Model
{
    public const RULE_REQUIRED = 'required';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';


    public function loadData($data){
        foreach($data as $key => $value){
            if(property_exists($this, $key)){
                $this->{$key} = $value;
            }
        }
    }

    abstract public function rules(): array;

    public array $errors = [];

    public function validate(){
        foreach($this->rules() as $attribute => $rules){
            $value = $this->{$attribute};
            foreach($rules as $rule){
                $ruleName = $rule;
                if(!is_string($ruleName)){
                    $ruleName = $rule[0];
                }
                if($ruleName === SELF::RULE_REQUIRED && !$value){
                    $this->addError($attribute, SELF::RULE_REQUIRED);
                }
                if($ruleName === SELF::RULE_MIN && strlen($value) < $rule['min']){
                    $this->addError($attribute, SELF::RULE_MIN, $rule);
                }
                if($ruleName === SELF::RULE_MAX && strlen($value) > $rule['max']){
                    $this->addError($attribute, SELF::RULE_MAX, $rule);
                }
                if($ruleName === SELF::RULE_MATCH && $value !== $this->{$rule['match']}){
                    $this->addError($attribute, SELF::RULE_MATCH, $rule);
                }
            }
        }

        return empty($this->errors);
    }

    public function addError(string $attribute, string $rule, $params = [])
    {
        $message = $this->errorMessages()[$rule] ?? '';
        foreach($params as $key => $value){
            $message = str_replace("{{$key}}", $value, $message);
        }
        $this->errors[$attribute][] = $message;
    }

    public function errorMessages(){
        return [
            SELF::RULE_REQUIRED => 'This field is required',
            SELF::RULE_MIN => 'Min length of this field must be {min}',
            SELF::RULE_MAX => 'Max length of this field must be {max}',
            SELF::RULE_MATCH => 'This field must be the same as {match}'
        ];
    }

    public function hasError($attribute) {
        return $this->errors[$attribute] ?? false;
    }

    public function getFirstError($attribute){
        return $this->errors[$attribute][0] ?? false;
    }
}