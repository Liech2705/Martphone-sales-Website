<?php
//session_start();
//require("chksession.php");
//*****************************************************************************
//[Modul name]: function.php
//[Purpose]   : Quan ly all function co lien quan toi PHP
//[Date]      : 31.10.05
//[Creator]   : PhongCT+HieuNK+DuongNT
//[Detail]    : Nhung ham khac nam trong trang FunctionJS.js
//******************************************************************************
//==============================================================================================

//********************************************************
//[Creator]   : PhongCT
//********************************************************
function chuanhoaxau($str)
{
    if (empty($str)) {
        return $str;
    }
    
    $str = trim($str);
    // Thay thế nhiều khoảng trắng bằng một khoảng trắng
    while (strpos($str, '  ') !== false) {
        $str = str_replace('  ', ' ', $str);
    }
    return $str;
}  

//********************************************************
//[Creator]   : PhongCT
//********************************************************
function ngaythangnam($namthangngay) // Date: USA -> France [(0000-00-00) -> (00-00-0000)]
{
    if (empty($namthangngay)) {
        return '';
    }
    
    $nam = substr($namthangngay, 0, 4);
    $thang = substr($namthangngay, 5, 2);
    $ngay = substr($namthangngay, 8, 2);
    return "$ngay-$thang-$nam";
}

//********************************************************
//[Creator]   : PhongCT
//********************************************************
function nam_tn($namthangngay) // Year of USA (0000-00-00)
{
    if (empty($namthangngay)) {
        return '';
    }
    return substr($namthangngay, 0, 4);
}

//********************************************************
//[Creator]   : PhongCT
//********************************************************
function namthangngay($ngaythangnam) // Date: France -> USA [(00-00-0000) -> (0000-00-00)]
{
    if (empty($ngaythangnam)) {
        return '';
    }
    
    $ngay = substr($ngaythangnam, 0, 2);
    $thang = substr($ngaythangnam, 3, 2);
    $nam = substr($ngaythangnam, 6, 4);
    return "$nam-$thang-$ngay";
}

//********************************************************
//[Creator]   : PhongCT
//********************************************************
function taomadm($bang, $truongma, $kytudungdau = '')
{
    require("dbcon.inc");
    $sql = "SELECT $truongma FROM $bang ORDER BY $truongma DESC LIMIT 1";
    $result = mysqli_query($link, $sql);
    $len_kt = strlen($kytudungdau);
    $ma_phanso = 0;

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $ma_phanso = (int)substr($row[$truongma], $len_kt) + 1;
    } else {
        $ma_phanso = 1;
    }

    $so = max(5, strlen($ma_phanso)); // Minimum 5 digits
    $sau = $kytudungdau . str_pad($ma_phanso, $so, '0', STR_PAD_LEFT);
    
    mysqli_close($link);
    return $sau;
}

//********************************************************
//[Creator]   : PhongCT
//********************************************************
function identity_int($bang, $truongma)
{
    require("dbcon.inc");
    $sql = "SELECT MAX($truongma) as max_id FROM $bang";
    $result = mysqli_query($link, $sql);
    $max = 0;

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $max = (int)$row['max_id'];
    }
    
    mysqli_close($link);
    return $max + 1;
}

//********************************************************
//[Creator]   : HieuNK
//********************************************************
function taoma($bang, $truongma)
{
    require("dbcon.inc");
    $sql = "SELECT MAX($truongma) as max_id FROM $bang";
    $result = mysqli_query($link, $sql);
    $max = 0;

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $max = (int)$row['max_id'];
    }
    
    mysqli_close($link);
    return $max + 1;
}

//********************************************************
//[Creator]   : AnhPT
//********************************************************
function redirect($url, $message = "", $delay = 0) 
{
    $delay = (int)$delay;
    echo "<meta http-equiv='refresh' content='$delay;url=$url'>";
    if (!empty($message)) {
        echo "<div style='font-family: Arial, Sans-serif; font-size: 14pt;' align='center'>$message</div>";
    }
    exit;
}

//********************************************************
//[Creator]   : AnhPT
//********************************************************
function Doitienrachu($str_nu)
{
    if (!is_numeric($str_nu) || $str_nu <= 0) {
        return "Không đồng";
    }

    $chuoi_ = "";
    $socat = str_split(strrev($str_nu));
    $stt = count($socat);
    
    $cb = [];
    $number_words = [
        '0' => 'Không',
        '1' => 'Một',
        '2' => 'Hai',
        '3' => 'Ba',
        '4' => 'Bốn',
        '5' => 'Năm',
        '6' => 'Sáu',
        '7' => 'Bảy',
        '8' => 'Tám',
        '9' => 'Chín'
    ];

    foreach ($socat as $i => $digit) {
        $cb[$i + 1] = $number_words[$digit];
    }

    for ($k = $stt; $k > 0; $k--) {
        $sh = ceil($k / 3);
        $sdr = $k % 3;
        
        // Xử lý đơn vị
        $dvt_l = '';
        if ($sh > 1 && $sh <= 2) $dvt_l = 'Nghìn,';
        elseif ($sh > 2 && $sh <= 3) $dvt_l = 'Triệu,';
        elseif ($sh > 3 && $sh <= 4) $dvt_l = 'Tỷ,';

        // Xử lý số
        if ($sdr == 0) { // Trăm
            if ($socat[$k-1] == '0' && isset($socat[$k-2]) && $socat[$k-2] == '0' && isset($socat[$k-3]) && $socat[$k-3] == '0') {
                $dvt_n = '';
                $cb[$k] = '';
            } else {
                $dvt_n = 'Trăm';
            }
        } elseif ($sdr == 2) { // Chục
            if ($socat[$k-1] == '1') {
                $cb[$k] = 'Mười';
                $dvt_n = '';
            } else {
                $dvt_n = 'Mươi';
                if ($socat[$k-1] == '0') {
                    if (isset($socat[$k-2]) && $socat[$k-2] != '0') {
                        $cb[$k] = 'Linh';
                        $dvt_n = '';
                    } else {
                        $cb[$k] = '';
                        $dvt_n = '';
                    }
                }
            }
        } elseif ($sdr == 1) { // Đơn vị
            if ($socat[$k-1] == '4' && (!isset($socat[$k]) || $socat[$k] != '1')) {
                $cb[$k] = 'Tư';
            }
            if ($socat[$k-1] == '5' && $k != $stt) {
                if (isset($socat[$k]) && $socat[$k] != '0') {
                    $cb[$k] = 'Lăm';
                }
            }
            if ($socat[$k-1] == '0') {
                $cb[$k] = '';
            }
            if ($socat[$k-1] == '1' && isset($socat[$k]) && $socat[$k] >= '2') {
                $cb[$k] = 'Mốt';
            }
            
            $dvt_n = ($socat[$k-1] == '0' && isset($socat[$k]) && $socat[$k] == '0' && isset($socat[$k+1]) && $socat[$k+1] == '0') ? '' : $dvt_l;
            
            $cb[$k] = ($k != $stt) ? strtolower($cb[$k]) : $cb[$k];
            $dvt_n = strtolower($dvt_n);
        }

        $chuoi_ = ($cb[$k] . ' ' . $dvt_n . ' ') . $chuoi_;
    }

    $chuoi_ = chuanhoaxau($chuoi_);
    $chuoi_ = trim($chuoi_, ',') . ' đồng';
    return ucfirst($chuoi_);
}

//********************************************************
//[Creator]   : PhongCT
//[Detail]    : Lay ten tu ma
//********************************************************
function layten($table, $field_ten, $value_ma, $field_ma, $value_ma2 = "", $field_dieukien2 = "")
{
    require("dbcon.inc");
    
    $where = '';
    if (!empty($value_ma2)) {
        $where = " AND $field_dieukien2 = '" . mysqli_real_escape_string($link, $value_ma2) . "'";
    }
    
    $sql = "SELECT $field_ten FROM $table WHERE $field_ma = '" . mysqli_real_escape_string($link, $value_ma) . "' $where";
    $result = mysqli_query($link, $sql);
    $ten = '';

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_object($result);
        $ten = $row->$field_ten;
    }
    
    mysqli_close($link);
    return $ten;
}
?>