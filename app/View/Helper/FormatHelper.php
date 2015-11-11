<?php

/**
 * View Helper
 */
App::uses('AppHelper', 'View/Helper');

class FormatHelper extends AppHelper
{

    public function phoneNumber($phoneNumber)
    {
        $prefix = array(
            'phone' => array(
                '20', '210', '211', '218', '219', '22', '230', '231', '240', '241', '25', '26', '27', '280', '281', '29',
                '30', '31', '320', '321', '33', '350', '351', '36', '37', '38', '39',
                '4',
                '500', '501', '510', '511', '52', '53', '54', '55', '56', '57', '58', '59',
                '60', '61', '62', '63', '64', '650', '651', '56', '67', '68',
                '70', '710', '711', '72', '73', '74', '70', '76', '77', '680', '781', '79',
                '8',
            ),
            'cell'  => array(
                '90', '91', '92', '93', '94', '95', '96', '97', '98', '992', '96', '97', '98', '999',
                '120', '121', '122', '123', '124', '125', '126', '127', '128', '129',
                '162', '163', '164', '165', '166', '167', '168', '169',
                '186', '187', '188',
                '199',
            ),
        );

        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        $first = '';
        if (strlen($phoneNumber) && $phoneNumber[0] == '0') {
            $first       = $phoneNumber[0];
            $phoneNumber = substr($phoneNumber, 1);
        }

        foreach ($prefix['cell'] as $item) {
            if (strpos($phoneNumber, $item) === 0) {
                $areaCode    = $item;
                $phoneNumber = substr($phoneNumber, strlen($areaCode));
                return $this->_vietnamCellphone($areaCode, $phoneNumber);
            }
        }

        foreach ($prefix['phone'] as $item) {
            if (strpos($phoneNumber, $item) === 0) {
                $areaCode    = $item;
                $phoneNumber = substr($phoneNumber, strlen($areaCode));
                return $this->_vietnamPhone($areaCode, $phoneNumber);
            }
        }

        return '#' . $phoneNumber . '#';
    }

    private function _vietnamCellphone($areaCode, $phoneNumber)
    {
        $countryCode = '+84';

        if (strlen($phoneNumber) > 7) {
            $nextThree   = substr($phoneNumber, -7, 3);
            $lastFour    = substr($phoneNumber, -4, 4);
            $phoneNumber = $countryCode . ' ' . $areaCode . '-' . $nextThree . '-' . $lastFour;
        } elseif (strlen($phoneNumber) == 7) {
            $nextThree   = substr($phoneNumber, -7, 3);
            $lastFour    = substr($phoneNumber, -4, 4);
            $phoneNumber = $countryCode . ' ' . $areaCode . '-' . $nextThree . '-' . $lastFour;
        } else {
            $phoneNumber = '#' . $phoneNumber . '#';
        }

        return $phoneNumber;
    }

    private function _vietnamPhone($areaCode, $phoneNumber)
    {
        $countryCode = '+84';

        if (strlen($phoneNumber) == 8) {
            $nextOne     = substr($phoneNumber, -7, 1);
            $nextThree   = substr($phoneNumber, -7, 3);
            $lastFour    = substr($phoneNumber, -4, 4);
            $phoneNumber = $countryCode . ' (' . $areaCode . ') ' . $nextOne . '-' . $nextThree . '-' . $lastFour;
        } elseif (strlen($phoneNumber) == 7) {
            $nextThree   = substr($phoneNumber, -7, 3);
            $lastFour    = substr($phoneNumber, -4, 4);
            $phoneNumber = $countryCode . ' (' . $areaCode . ') ' . $nextThree . '-' . $lastFour;
        } else {
            $phoneNumber = '#' . $phoneNumber . '#';
        }

        return $phoneNumber;
    }

}
