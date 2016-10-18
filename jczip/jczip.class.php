<?php

/**
 * Codes that compress a file or folder (with files/subfolders) into to a zip file
 * this class extends to the zip library of php ZipArchive
 * 
 * @return zip file
 */
class compressToZip extends ZipArchive
{

    /**
     * Create the zip file
     * 
     * @var string source = path to the file/folder to be zip
     * @var string destination = path to the folder of the output
     * 
     * @return string - path to the output
     */
    public function createZip($_source, $destination = '')
    {

        $sources = array();
        if (!is_array($_source))
        {
            $sources[] = $_source;
        } else
        {
            $sources = $_source;
        }

        foreach ($sources as $source)
        {
            $_validSource = $this->_checkSource($source);

            if ($_validSource !== 'INVALID')
            {

                if (basename($source) == '.')
                {
                    $filename = date('Ymd') . '.zip';
                } else
                {
                    $filename = basename($source) . '.zip';
                }
                $pathname = $destination . '/' . $filename;

                if ($this->open($pathname, ZIPARCHIVE::CREATE))
                {
                    echo 'Starting to create <strong>' . $filename . '</strong>... <br/>';
                    echo '<em>' . $pathname . '</em> has been created. <hr/>';
                } else
                {
                    echo '<hr/> <h3> Some files are missing, zip cannot be created </h3>';
                }

                if ($_validSource === 'FILE')
                {

                    if (is_readable($source))
                    {
                        $this->addFile($source);
                        echo '<em>' . $source . '</em> was added to the <strong>' . $filename . '</strong><br/>';
                    } else
                    {
                        echo "<span style='color:red'>SKIPPED: " . $source . '. <strong>File does not exists ' . $filename . '</strong></span><br/>';
                    }
                } else if ($_validSource === 'DIRECTORY')
                {

                    $this->_addDirectory($source, $destination, $filename);
                } else
                {
                    return false;
                }
            } //end if

            $this->close(); //close the zip

            /**
             * Check if the file really created and exists
             */
            if (file_exists($pathname))
            {
                echo "<hr/> <strong>$filename</strong> successfully created!";
                echo "<br/>Path: <em>" . realpath($pathname) . "</em>";
            } else
            {
                echo '<hr/> Status: ' . $this->getStatusString() . '<br/>';
                echo '<h3> Some files are missing or unreadable, zip creation rolled back. </h3>';
            }//end if
        }
    }

//end public function createZip

    /**
     * Extract a zip file
     * 
     * @param string $_source zip file to be extracted
     * @param string $destination the path where the zip be extracted
     * 
     * @return bool True on success and False on failure
     */
    public function extractZip($_source, $destination = '.')
    {

        $sources = array();
        if (!is_array($_source))
        {
            $sources[] = $_source;
        } else
        {
            $sources = $_source;
        }

        foreach ($sources as $source)
        {

            $finished = FALSE;

            if (pathinfo($source, PATHINFO_EXTENSION) == 'zip')
            {

                $isOpen = $this->open($source);

                if ($isOpen)
                {

                    echo "Extracting <strong>" . pathinfo($source, PATHINFO_BASENAME) . "</strong>...<hr/>";

                    for ($i = 0; $i < $this->numFiles; $i++)
                    {

                        $extractedFile = $this->extractTo($destination, array($this->getNameIndex($i)));

                        echo "<strong>- - - " . $this->getNameIndex($i) . "</strong> has been extracted<br/>";

                        $finished = TRUE;
                    }

                    if ($finished)
                    {
                        echo "<hr/><strong>Success!</strong> <br/> <hr/>";
                        echo "Number of Files Extracted: <strong>" . $i . "</strong><br/>";
                        echo "Zip File: <strong>" . pathinfo($source, PATHINFO_BASENAME) . "</strong><br/>";
                        echo "Extracted to: <strong>" . realpath($destination) . "</strong><hr/>";
                    }
                } else
                {
                    echo "<h3>Unable to open zip file</h3>";
                }//end-if
            } else
            {
                echo "<h3>Invalid zip file</h3>";
            }//end-if
        }
    }

//end method extractZip

    /**
     * This method will check if the source to be zip is a file or folder
     * 
     * @param string $source
     * @return string
     */
    private function _checkSource($source)
    {

        /**
         * Check if source is a file or directory
         * 
         * @return string - type of the source
         */
        if (is_file($source))
        {

            return 'FILE';
        } else if (is_dir($source))
        {

            return 'DIRECTORY';
        } else
        {

            return 'INVALID';
        } //end if
    }

// end private function _checkSource

    /**
     * Recursively go thru the directory and add files and folder in it to the zip file
     * 
     * @param type $source
     * @param type $destination
     * 
     * @return string path of the output
     */
    private function _addDirectory($source, $destination, $filename)
    {

        $dir = opendir($source); //open the source directory
        // loop thru the current directory
        while (( $directory = readdir($dir)) !== false)
        {
            // excludes . and .. (elipsis)
            if (( $directory != '.' ) && ( $directory != '..' ))
            {
                if (is_readable($source . '/' . $directory))
                {
                    // current directory is has a sub-directory
                    if (is_dir($source . '/' . $directory))
                    {

                        /**
                         * Check if the directory is empty
                         * if TRUE, add empty directory
                         */
                        if (count(glob($source . '/' . $directory) === 0))
                        {
                            $this->addEmptyDir($source . '/' . $directory);
                            echo '<em>' . $source . '/' . $directory . '</em> was added to the <strong>' . $filename . '</strong><br/>';
                        }

                        $this->_addDirectory($source . '/' . $directory, $destination, $filename);
                    } else
                    { // if it has a file only
                        $this->addFile($source . '/' . $directory); //add it to the zip
                        echo '<em>' . $source . '/' . $directory . '</em> was added to the <strong>' . $filename . '</strong><br/>';
                    }
                } else
                {
                    echo "<span style='color:red'>SKIPPED: " . $source . '/' . $directory . '. <strong>File does not exists ' . $filename . '</strong></span><br/>';
                }
            } //end-if
        } //end while
        closedir($dir); //close the directory
    }

//end private function _recursive
}
