<?php

namespace phpaes;

class Util {

    // Thar' be dragons below
    // aka my crappy copy/pasta helper functions -- don't judge me!

    public function bytestring($data) {
        $a = new \SplFixedArray(strlen($data));
        for ($i = 0; $i < strlen($data); $i++) {
            $a[$i] = str_pad(ord($data[$i]), 3, ' ', STR_PAD_LEFT);
        }
        return implode(' ', $a->toArray());
    }

    public function renderTable($data) {

        // better have some kind of data
        if (empty($data) || !is_array($data)) {
            return "Empty set";
        }

        // validate that $data is an array of arrays
        foreach ($data as $elm) {
            if (!is_array($elm)) {
                return 'A value in the renderTable set was no an array.';
            }
            foreach ($elm as $elmValue) {
                if (is_array($elmValue) || is_object($elmValue)) {
                    return 'Too many levels of array/objects in the renderTable dataset';
                }
            }
        }

        // determine widths of titles
        $colWidths = array();
        foreach ($data as $row) {
            foreach ($row as $title => $value) {
                $colWidths[$title] = mb_strlen($title);
            }
        }

        // determine widths of columns
        foreach ($data as $row) {
            foreach ($row as $title => $value) {
                if (is_null($value)) {
                    $value = 'NULL';
                }
                if ($value === true) {
                    $value = 'TRUE';
                }
                if ($value === false) {
                    $value = 'FALSE';
                }
                if ($colWidths[$title] < mb_strlen($value, 'UTF-8')) {
                    $colWidths[$title] = mb_strlen($value, 'UTF-8');
                }
            }
        }

        // generate horizontal border
        $horizontalBorder = '+';
        foreach ($colWidths as $title => $width) {
            $horizontalBorder .= str_repeat('-', $width + 2) . "+";
        }
        $horizontalBorder .= "\n";

        // star the output
        $output = '';

        // print titles
        $output .= $horizontalBorder;
        $output .= '|';
        foreach ($colWidths as $title => $width) {
            $output .= ' ' . $this->mb_str_pad($title, $colWidths[$title]) . ' |';
        }
        $output .= "\n";
        $output .= $horizontalBorder;

        // print contents
        foreach ($data as $row) {
            $output .= "|";
            foreach ($colWidths as $title => $width) {
                $value = array_key_exists($title, $row) ? $row[$title] : '';
                if (is_null($value)) {
                    $value = 'NULL';
                }
                if ($value === true) {
                    $value = 'TRUE';
                }
                if ($value === false) {
                    $value = 'FALSE';
                }
                if (is_numeric($value)) {
                    $output .= ' ' . $this->mb_str_pad($value, $colWidths[$title], ' ', STR_PAD_LEFT) . ' |';
                }
                else {
                    $output .= ' ' . $this->mb_str_pad($value, $colWidths[$title], ' ', STR_PAD_RIGHT) . ' |';
                }
            }
            $output .= "\n";
        }
        $output .= $horizontalBorder;

        return $output;
    }

    public function mb_str_pad($input, $pad_length, $pad_string=' ', $pad_type=STR_PAD_RIGHT) {
        $diff = strlen($input) - mb_strlen($input, 'UTF-8');
        return str_pad($input, $pad_length+$diff, $pad_string, $pad_type);
    }

}