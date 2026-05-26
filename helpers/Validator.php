<?php
/**
 * Validation Helper
 * مساعد التحقق من صحة البيانات
 */

class Validator {
    
    private $errors = [];
    private $data = [];

    /**
     * Constructor
     */
    public function __construct($data = []) {
        $this->data = $data;
    }

    /**
     * Required Field
     * الحقل مطلوب
     */
    public function required($field, $message = null) {
        if (empty($this->data[$field] ?? null)) {
            $this->errors[$field][] = $message ?? ucfirst($field) . ' مطلوب';
        }
        return $this;
    }

    /**
     * Min Length
     * الحد الأدنى للطول
     */
    public function minLength($field, $min, $message = null) {
        if (isset($this->data[$field]) && strlen($this->data[$field]) < $min) {
            $this->errors[$field][] = $message ?? ucfirst($field) . ' يجب أن يكون على الأقل ' . $min . ' أحرف';
        }
        return $this;
    }

    /**
     * Max Length
     * الحد الأقصى للطول
     */
    public function maxLength($field, $max, $message = null) {
        if (isset($this->data[$field]) && strlen($this->data[$field]) > $max) {
            $this->errors[$field][] = $message ?? ucfirst($field) . ' يجب أن لا يتجاوز ' . $max . ' أحرف';
        }
        return $this;
    }

    /**
     * Email Validation
     * التحقق من البريد الإلكتروني
     */
    public function email($field, $message = null) {
        if (isset($this->data[$field]) && !Security::validateEmail($this->data[$field])) {
            $this->errors[$field][] = $message ?? 'البريد الإلكتروني غير صحيح';
        }
        return $this;
    }

    /**
     * Phone Validation
     * التحقق من رقم الهاتف
     */
    public function phone($field, $message = null) {
        if (isset($this->data[$field]) && !Security::validatePhone($this->data[$field])) {
            $this->errors[$field][] = $message ?? 'رقم الهاتف غير صحيح';
        }
        return $this;
    }

    /**
     * Numeric
     * التحقق من أن القيمة رقمية
     */
    public function numeric($field, $message = null) {
        if (isset($this->data[$field]) && !is_numeric($this->data[$field])) {
            $this->errors[$field][] = $message ?? ucfirst($field) . ' يجب أن يكون رقماً';
        }
        return $this;
    }

    /**
     * Min Value
     * القيمة الحد الأدنى
     */
    public function min($field, $min, $message = null) {
        if (isset($this->data[$field]) && $this->data[$field] < $min) {
            $this->errors[$field][] = $message ?? ucfirst($field) . ' يجب أن يكون ' . $min . ' على الأقل';
        }
        return $this;
    }

    /**
     * Max Value
     * القيمة الحد الأقصى
     */
    public function max($field, $max, $message = null) {
        if (isset($this->data[$field]) && $this->data[$field] > $max) {
            $this->errors[$field][] = $message ?? ucfirst($field) . ' يجب أن لا يتجاوز ' . $max;
        }
        return $this;
    }

    /**
     * Get Errors
     * الحصول على الأخطاء
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * Passes
     * التحقق من عدم وجود أخطاء
     */
    public function passes() {
        return empty($this->errors);
    }

    /**
     * Fails
     * التحقق من وجود أخطاء
     */
    public function fails() {
        return !empty($this->errors);
    }
}