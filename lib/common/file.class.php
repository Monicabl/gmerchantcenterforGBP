<?php
/**
 * Google Merchant Center
 *
 * @author    BusinessTech.fr - https://www.businesstech.fr
 * @copyright Business Tech - https://www.businesstech.fr
 * @license   Commercial
 *
 *           ____    _______
 *          |  _ \  |__   __|
 *          | |_) |    | |
 *          |  _ <     | |
 *          | |_) |    | |
 *          |____/     |_|
 */

class BT_GmcFile
{
    /**
     * @var object $obj : store the current object
     */
    public static $obj = null;

    /**
     * @var string $sBuffer : current buffer
     */
    public $sBuffer = null;

    /**
     * @var string $sError
     */
    public $sError = null;

    /**
     * @var string $sLogFile
     */
    public $sLogFile = null;

    /**
     * @var string $sDate
     */
    public $sDate = null;

    /**
     * @static array $_AVAILABLE_CONF : all available params
     */
    static private $_AVAILABLE_CONF = array(
        'verbose' => array('value' => false, 'condition' => 'is_bool'),
        'add' => array('value' => false, 'condition' => 'is_bool'),
        'buffer' => array('value' => false, 'condition' => 'is_bool'),
        'error' => array('value' => false, 'condition' => 'is_bool'),
        'strip_tags' => array('value' => false, 'condition' => 'is_bool'),
        'rename' => array('value' => false, 'condition' => 'is_bool'),
        'delete' => array('value' => false, 'condition' => 'is_bool'),
        'lock' => array('value' => null, 'condition' => array('LOCK_SH', 'LOCK_EX', 'LOCK_UN', 'LOCK_NB')),
    );

    /**
     * @static array $_PROJECT_NAME
     */
    static private $_PROJECT_NAME = null;

    /**
     * @constants
     */
    const ERR_READABLE_M = 'File has no write permission';
    const ERR_READABLE_N = 101;
    const ERR_FILE_IS_DIR_M = 'File is a directory';
    const ERR_FILE_IS_DIR_N = 102;
    const ERR_OPEN_FILE_M = 'File can\'t be opened';
    const ERR_OPEN_FILE_N = 103;
    const ERR_READABLE_DIR_M = 'Directory has no write permission';
    const ERR_READABLE_DIR_N = 104;
    const ERR_LENGTH_DATA_WRITE_M = 'Different size of written data to data to write';
    const ERR_LENGTH_DATA_WRITE_N = 105;
    const ERR_DIR_ALREADY_EXIST_M = 'Renamed file already exists';
    const ERR_DIR_ALREADY_EXIST_N = 106;
    const ERR_RENAME_FILE_M = '';
    const ERR_RENAME_FILE_N = 107;
    const ERR_COPY_FILE_M = 'Copying source file failed';
    const ERR_COPY_FILE_N = 108;
    const ERR_ADD_FILE_M = 'Add content to the current file failed';
    const ERR_ADD_FILE_N = 109;
    const ERR_LOCK_FILE_M = 'File locking failed';
    const ERR_LOCK_FILE_N = 110;


    /**
     * __construct()
     *
     * @param string $sLogFile
     *
     */
    public function __construct($sLogFile = null)
    {
        $this->sError = $this->sBuffer = '';
        $this->sDate = date("d-m-Y à H:i:s");
        $this->sLogFile = $sLogFile;
    }

    /**
     * __destruct()
     */
    public function __destruct()
    {
        // write error log file if there is any file name and errors set
        if (!empty($this->sError) && !empty($this->sLogFile)) {
            self::write($this->sLogFile, $this->sError, true);
        }
    }

    /**
     * verbose() method display verbose
     *
     * @param string $sContent
     * @param bool $bError - make difference between standard buffer to error buffer
     * @param string $sCode
     */
    public function verbose($sContent, $bError = false, $sCode = 'VERBOSE')
    {
        // use case - set buffer
        if (self::$_AVAILABLE_CONF['buffer'] === true) {
            if ($bError === true) {
                $this->sError .= '[ ' . $sCode . ' ] ' . date("d-m-Y à H:i:s") . ' => ' . $sContent . "\n";
            } else {
                $this->sBuffer .= '[ ' . $sCode . ' ] ' . date("d-m-Y à H:i:s") . ' => ' . $sContent . "\n";
            }
        }
        // display in any case
        echo '[ ' . $sCode . ' ] ' . date("d-m-Y à H:i:s") . ' => ' . $sContent . "\n";
    }

    /**
     * writeFile() method write a file
     *
     * @access            :    $ojb->write($name,$content, false , false , false);
     * @param string $sFileName
     * @param string $sContent
     * @param bool $bVerbose - display comments
     * @param bool $bAdd - adding data
     * @param bool $bStripTag - strip all HTML tags
     */
    public function write($sFileName, $sContent, $bVerbose = false, $bAdd = false, $bStripTag = false)
    {
        // use case - file as directory failed
        if (is_dir($sFileName)) {
            // throw exception
            throw new Exception($this->sDate . $sFileName . ' : ' . self::ERR_FILE_IS_DIR_M, self::ERR_FILE_IS_DIR_N);
        }

        // get folder name
        $dirName = dirname($sFileName);

        // recursive folder create
        self::createDir($dirName);

        // use case - test if directory is writable
        if (is_writable($dirName)) {
            // use case - strip HTML tags for database content
            if ($bStripTag === true || self::$_AVAILABLE_CONF['strip_tags']['value'] === true) {
                $sContent = strip_tags(str_replace(array('<br/>', '<BR/>', '<br>', '<BR>'), "\n", $sContent));
            }

            // use case - add content mode
            if ($bAdd === true || self::$_AVAILABLE_CONF['add']['value'] === true) {
                $sMode = 'a';
            } else {
                $sMode = 'wb';
            }
            // use case - test if file is openable
            $rHandle = fopen($sFileName, $sMode);

            if ($rHandle === false) {
                // throw exception
                throw new Exception($this->sDate . " $dirName : " . self::ERR_OPEN_FILE_M, self::ERR_OPEN_FILE_N);
            } else {
                $nDataLength = strlen($sContent);

                // if lock exists
                if (self::$_AVAILABLE_CONF['lock']['value'] !== null) {
                    if (flock($rHandle, $this->_sLock)) {
                        $nWrittenData = fwrite($rHandle, $sContent, $nDataLength);

                        flock($rHandle, LOCK_UN);
                    } else {
                        // throw exception
                        throw new Exception($this->sDate . " $dirName : " . self::ERR_LOCK_FILE_M,
                            self::ERR_LOCK_FILE_N);
                    }
                } else {
                    $nWrittenData = fwrite($rHandle, $sContent, $nDataLength);
                }
                // close
                fclose($rHandle);

                // check string length
                if ($nDataLength != $nWrittenData) {
                    throw new Exception($this->sDate . " $sFileName : " . self::ERR_LENGTH_DATA_WRITE_M,
                        self::ERR_LENGTH_DATA_WRITE_N);
                } elseif ($bVerbose === true || self::$_AVAILABLE_CONF['verbose']['value'] === true) {
                    self::verbose(' ---File created ' . $sFileName, 'WRITING');
                }
            }
        } else {
            // throw exception
            throw new Exception($this->sDate . $dirName . ' : ' . self::ERR_READABLE_DIR_M, self::ERR_READABLE_DIR_N);
        }
    }

    /**
     * copy() method copy or rename file
     *
     * @param string $sSource
     * @param string $sDest
     * @param bool $bDelete
     * @param bool $bRename
     * @param bool $bVerbose
     */
    public function copy($sSource, $sDest, $bDelete = false, $bRename = false, $bVerbose = false)
    {
        // use case - check if destination folder exists
        $sDirDest = dirname($sDest);

        // check source file
        if (is_dir($sSource)) {
            // throw exception
            throw new Exception($this->sDate . $sSource . ' : ' . self::ERR_FILE_IS_DIR_M, self::ERR_FILE_IS_DIR_N);
        }
        // check destination
        if (is_dir($sDest)) {
            // throw exception
            throw new Exception($this->sDate . $sDest . ' : ' . self::ERR_FILE_IS_DIR_M, self::ERR_FILE_IS_DIR_N);
        } else {
            // recursive folder create
            self::createDir($sDirDest);
        }

        // use case - check if folder is writable
        if (is_writable($sDirDest)) {
            // use case - check create or rename mode
            if ($bRename === true || self::$_AVAILABLE_CONF['rename']['value'] === true) {
                if (file_exists($sDest) === false) {
                    if (!copy($sSource, $sDest)) {
                        // throw exception
                        throw new Exception($this->sDate . $sSource . ' in to ' . $sDest . ' : ' . self::ERR_COPY_FILE_M,
                            self::ERR_COPY_FILE_N);
                    } elseif ($bVerbose === true || self::$_AVAILABLE_CONF['verbose']['value'] === true) {
                        self::verbose(' --- File ' . $sSource . ' has been copied ' . $sDest . ' in to directory ' . $sDirDest,
                            'WRITING');
                    }
                } elseif (!rename($sSource, $sDest)) {
                    // throw exception
                    throw new Exception($this->sDate . $sSource . ' in to ' . $sDest . ' : ' . self::ERR_RENAME_FILE_M,
                        self::ERR_RENAME_FILE_N);
                } elseif ($bVerbose === true || self::$_AVAILABLE_CONF['verbose']['value'] === true) {
                    self::verbose(' --- File ' . $sSource . ' has been renamed  as ' . $sDest . ' in to directory ' . $sDirDest,
                        'WRITING');
                }
            } // use case - check if file to copy already exists
            elseif (!file_exists($sDest)) {
                if (!copy($sSource, $sDest)) {
                    throw new Exception($this->sDate . $sSource . ' in to ' . $sDest . ' : ' . self::ERR_COPY_FILE_M,
                        self::ERR_COPY_FILE_N);
                } elseif ($bVerbose === true || self::$_AVAILABLE_CONF['verbose']['value'] === true) {
                    self::verbose(' --- File ' . $sSource . ' has been copied as ' . $sDest . ' in to directory ' . $sDirDest,
                        'WRITING');
                }
            } //  use case - display message
            elseif ($bVerbose === true || self::$_AVAILABLE_CONF['verbose']['value'] === true) {
                self::verbose(' --- File ' . $sSource . ' already exists as ' . $sDest . ' in to directory ' . $sDirDest,
                    'WRITING');
            }
            // test if have to delete source file
            if ($bDelete === true || self::$_AVAILABLE_CONF['delete']['value'] === true) {
                unlink($sSource);
                if ($bVerbose === true || self::$_AVAILABLE_CONF['verbose']['value'] === true) {
                    self::verbose(' --- File ' . $sSource . ' has been deleted', 'DELETING');
                }
            }
        } else {
            // throw exception
            throw new Exception($this->sDate . $sDirDest . ' : ' . self::ERR_READABLE_DIR_M, self::ERR_READABLE_DIR_N);
        }
    }

    /**
     * delete() delete file
     *
     * @param string $sFileName
     * @return bool
     */
    public function delete($sFileName)
    {
        if (file_exists($sFileName)) {
            if (unlink($sFileName)) {
                return true;
            }
        }
        return false;
    }

    /**
     * read() method read file
     *
     * @param string $sFile
     */
    public function read($sFile)
    {
        if (is_readable($sFile)) {
            return file_get_contents($sFile);
        }
        return false;
    }

    /**
     * init() method set options, display / add and rename file + add buffering
     * @param array $aOptions - accepted keys : verbose , add , strip_tags , rename , buffer , delete , lock
     */
    public static function init(array $aOptions)
    {

        // test existence of key / value to set as init values
        foreach ($aOptions as $sKey => $mValue) {
            if (array_key_exists($sKey, self::$_AVAILABLE_CONF)) {
                if (is_array(self::$_AVAILABLE_CONF[$sKey]['condition'])) {
                    if (in_array($mValue, self::$_AVAILABLE_CONF[$sKey]['condition'])) {
                        self::$_AVAILABLE_CONF[$sKey]['value'] = $mValue;
                    } else {
                        // throw exception if specified directory do not exist
                        throw new Exception('Specified value for ' . $sKey . ' is not avalaible value : ' . implode(' , ',
                                self::$_AVAILABLE_CONF[$sKey]['condition']));
                    }
                } elseif (self::$_AVAILABLE_CONF[$sKey]['condition']($mValue)) {
                    self::$_AVAILABLE_CONF[$sKey]['value'] = $mValue;
                } else {
                    // throw exception if specified directory do not exist
                    throw new Exception('Specified value for ' . $sKey . ' is not avalaible value : ' . self::$_AVAILABLE_CONF[$sKey]['condition']);
                }
            }
        }
    }

    /**
     * getFileName() Transform string to filename
     *
     * @param string $sText
     * @param string $ext = '.html'
     * @param string $sep = '-'
     * @return string
     */
    public static function getFileName($sText, $sExt = '.html', $sSep = "-")
    {
        // set special chars reference
        $aSpecialChars = array(
            '@',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�',
            '�'
        );
        $aChars = array(
            'a',
            'A',
            'A',
            'A',
            'A',
            'A',
            'A',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'O',
            'O',
            'O',
            'O',
            'O',
            'O',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'E',
            'E',
            'E',
            'E',
            'e',
            'e',
            'e',
            'e',
            'C',
            'c',
            'I',
            'I',
            'I',
            'I',
            'i',
            'i',
            'i',
            'i',
            'U',
            'U',
            'U',
            'U',
            'u',
            'u',
            'u',
            'u',
            'y',
            'N',
            'n'
        );

        // check special chars
        if (isset($sText[(strlen($sText) - 1)]) && in_array($sText[(strlen($sText) - 1)], $aSpecialChars)) {
            $sText = str_replace($aSpecialChars, $aChars, $sText);
        }

        // utf8 decode while needed
        if (mb_detect_encoding($sText, "auto", true) == 'UTF-8') {
            $sText = mb_convert_encoding($sText, 'UTF-8', 'auto');
        }
        // replace specific chars
        $tmp = preg_replace("/[^a-zA-Z0-9]/", $sSep, str_replace($aSpecialChars, $aChars, $sText));
        // if many '-' followed
        $tmp = preg_replace("/(-){2,}/", '-', $tmp);

        // get length
        $nLength = strlen($tmp);
        // check correct start
        if (isset($tmp[0]) && ($tmp[0] == $sSep || $tmp[0] == chr(32))) {
            $tmp = substr($tmp, 1, $nLength);
        }

        // check correct end
        if (substr($tmp, $nLength - 1) == $sSep) {
            $tmp = substr($tmp, 0, $nLength - 1);
        }

        // return value
        return (strtolower($tmp . $sExt));
    }


    /**
     * createDir() create recursively directory
     *
     * @param string $sDirName
     * @return mixed
     */
    public static function createDir($sDirName)
    {
        if (!empty($sDirName) && !is_dir($sDirName)) {
            if (self::createDir(dirname($sDirName))) {
                return mkdir($sDirName);
            }
        }
        return true;
    }

    /**
     * create() method create a singleton of object
     *
     * @param    string $sLogFile
     * @return  object    $obj
     */
    public static function create($sLogFile = null)
    {
        if (null === self::$obj) {
            self::$obj = new BT_GmcFile($sLogFile);
        }
        return self::$obj;
    }
}
