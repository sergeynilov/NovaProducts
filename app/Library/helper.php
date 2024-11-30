<?php

use Illuminate\Support\Facades\File;

if (! function_exists('isPgSql')) {
    function isPgSql(): bool
    {
        $dbConnection = config('database.default');
        return $dbConnection === 'pgsql';
    }
} // if (!function_exists('isPgSql')) {

if ( ! function_exists('pluralize3')) {
    function pluralize3($itemsLength, $noItemsText, $singleItemText, $multiItemsText)
    {
//        \Log::info(  varDump(gettype($itemsLength), ' -1 gettype($itemsLength)::') );
        if (gettype($itemsLength) === 'undefined') {
            return '';
        }
        if (gettype($itemsLength) === 'integer' && $itemsLength <= 0) {
            return $noItemsText;
        }
        if (gettype($itemsLength) === 'integer' && $itemsLength === 1) {
            return $singleItemText;
        }
        if (gettype($itemsLength) === 'integer' && $itemsLength > 1) {
            return $multiItemsText;
        }

        return '';
    }
} // if ( ! function_exists('pluralize3')) {


if ( ! function_exists('getValueLabelKeys')) {
    function getValueLabelKeys(array $arr): string
    {
        $keys = array_keys($arr);
        $ret_str = '';
        foreach ($keys as $next_key) {
            $ret_str .= $next_key . ',';
        }

        return trimRightSubString($ret_str, ',');
    }

} // if ( ! function_exists('getValueLabelKeys')) {


if ( ! function_exists('varDump')) {
    function varDump($var, $descr = '', bool $return_string = true)
    {
//        return;
//        \Log::info( '00 varDump $var ::' . print_r( $var, true  ) );
//        \Log::info( '000 varDump gettype($var) ::' . print_r( gettype($var), true  ) );

        if (is_null($var)) {
            $output_str = 'NULL :' . (! empty($descr) ? $descr . ' : ' : '') . 'NULL';
            if ($return_string) {
                return $output_str;
            }
            \Log::info($output_str);

            return;
        }
        if (is_scalar($var)) {
            $output_str = 'scalar => (' . gettype($var) . ') :' . (! empty($descr) ? $descr . ' : ' : '') . $var;
            if ($return_string) {
                return $output_str;
            }
            \Log::info($output_str);

            return;
        }
//        \Log::info( -1);
        if (is_array($var)) {
//            \Log::info( -2);
            $output_str = '[]';
            if (isset($var[0])) {
//                \Log::info( -22);
                if (is_subclass_of($var[0], 'Illuminate\Database\Eloquent\Model')) {
//                    \Log::info( -23);
                    $collectionClassBasename = class_basename($var[0]);
                    $output_str = ' Array(' . count(collect($var)->toArray()) . ' of ' . $collectionClassBasename . ') :' . (! empty($descr) ? $descr . ' : ' : '') . print_r(collect($var)->toArray(),
                            true);
                } else {
//                    \Log::info( -24);
                    $output_str = 'Array(' . count($var) . ') :' . (! empty($descr) ? $descr . ' : ' : '') . print_r($var,
                            true);
                }
            } else {
//                \Log::info( -41);
                $output_str = 'Array(' . count($var) . ') :' . (! empty($descr) ? $descr . ' : ' : '') . print_r($var,
                        true);
            }

//            \Log::info( -3);
            if ($return_string) {
                return $output_str;
            }

//            \Log::info($output_str );
            return;
        }

//        \Log::info( -4);
//        \Log::info( '-0 varDump class_basename($var) ::' . print_r( class_basename($var), true  ) );
        if (class_basename($var) === 'Request' or class_basename($var) === 'LoginRequest') {
            $request = request();
            $requestData = $request->all();
            $output_str = 'Request:' . (! empty($descr) ? $descr . ' : ' : '') . print_r($requestData,
                    true);
            if ($return_string) {
                return $output_str;
            }
            \Log::info($output_str);

            return;
        }

        if (class_basename($var) === 'LengthAwarePaginator' or class_basename($var) === 'Collection') {
            $collectionClassBasename = '';
            if (isset($var[0])) {
                $collectionClassBasename = class_basename($var[0]);
            }
            $output_str = ' Collection(' . count($var->toArray()) . ' of ' . $collectionClassBasename . ') :' . (! empty($descr) ? $descr . ' : ' : '') . print_r($var->toArray(),
                    true);
            if ($return_string) {
                return $output_str;
            }
            \Log::info($output_str);

            return;
        }

        /*        if (!is_subclass_of($model, 'Illuminate\Database\Eloquent\Model')) {
                }*/
        if (gettype($var) === 'object') {
            if (is_subclass_of($var, 'Illuminate\Database\Eloquent\Model')) {
//            if ( get_parent_class($var) == 'Illuminate\Database\Eloquent\Model' ) {
                $output_str = ' (Model Object of ' . get_class($var) . ') :' . (! empty($descr) ? $descr . ' : ' : '') . print_r($var/*->getAttributes()*/ ->toArray(),
                        true);
                if ($return_string) {
                    return $output_str;
                }
                \Log::info($output_str);

                return;
            }
            $output_str = ' (Object of ' . get_class($var) . ') :' . (! empty($descr) ? $descr . ' : ' : '') . print_r((array)$var,
                    true);
            if ($return_string) {
                return $output_str;
            }
            \Log::info($output_str);

            return;
        }
        //        \Log::info( '-2 varDump $var ::' . print_r( $var, true  ) );
        //        \Log::info( '-3 varDump gettype($var) ::' . print_r( gettype($var), true  ) );
    }
} // if ( ! function_exists('varDump')) {


if ( ! function_exists('getFilenameBasename')) {
    function getFilenameBasename($file)
    {
        return File::name($file);
    }
} // if (! function_exists('getFilenameBasename')) {

if ( ! function_exists('trimRightSubString')) {
    function trimRightSubString(
        string $s,
        string $substr
    ): string {
        $res = preg_match('/(.*?)(' . preg_quote($substr, "/") . ')$/si', $s, $A);
        if ( ! empty($A[1])) {
            return $A[1];
        }

        return $s;
    }
} // if (! function_exists('trimRightSubString')) {


if ( ! function_exists('getBrowserType')) {
    function getBrowserType(): string {
        if(\Browser::isFirefox()) return 'Firefox';
        if(\Browser::isOpera()) return 'Opera';
        if(\Browser::isChrome()) return 'Chrome';
        if(\Browser::isSafari()) return 'Safari';
        if(\Browser::isIE()) return 'Internet Explorer';
        return 'Unkknown'; // TODO for konquer
    }
} // if (! function_exists('trimRightSubString')) {



if (! function_exists('getModelTitle')) {
    function getModelTitle(string $dataType): string
    {
        $a = \Str::of($dataType)->explode('\\');
        return ((count($a) === 3)) ? $a[2] : '';

    }
} // if (!function_exists('getModelTitle')) {


