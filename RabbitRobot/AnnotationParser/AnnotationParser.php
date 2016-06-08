<?php

namespace RabbitRobot\AnnotationParser;

use SplFileObject;

/**
 * AnnotationParser
 * @author Fabio Covolo Mazzo
 * @date 07/06/2016
 */
final class AnnotationParser
{
    /**
     * Find first occurency of annotation, and returns true if is found.
     * Note that only finds the annotation string for perfomrance purposes, you must
     * confirme this is a annotation after load a class using AnnotationReader.
     *
     * @param $path
     * @param $annotation
     *
     * @return boolean
     */
    public function findAnnotion($path, $annotation)
    {
        $content = $this->getFileContent($path);

        if (null === $content) {
            return false;
        }

        if(strpos($content, $annotation)===false) {
            return false;
        }
        return true;
    }

    /**
     * Gets the content of the file right up to the given line number.
     *
     * @param string  $filename   The name of the file to load.
     * @param integer $lineNumber The number of lines to read from file.
     *
     * @return string The content of the file.
     */
    private function getFileContent($filename)
    {
        if ( ! is_file($filename)) {
            return null;
        }

        $content = '';
        $file = new SplFileObject($filename);
        while (!$file->eof()) {
            $content .= $file->fgets();
        }

        return $content;
    }
}
