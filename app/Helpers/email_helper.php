<?php

if (!function_exists('send_activity_email')) {
    /**
     * @param string $to      Email address
     * @param string $subject Subject
     * @param string $message HTML message body
     * @return bool
     */
    function send_activity_email($to, $subject, $message)
    {
        $email = \Config\Services::email();

        // If email is empty, just skip (some students might not have it yet)
        if (empty($to)) {
            return false;
        }

        $email->setTo($to);
        $email->setSubject($subject);
        $email->setMessage($message);
        $email->setMailType('html');

        if ($email->send()) {
            return true;
        } else {
            // Log error if needed: log_message('error', $email->printDebugger(['headers']));
            return false;
        }
    }
}
