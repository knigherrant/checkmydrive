<?php
/**
 * @package     Joomla.Platform
 * @subpackage  FileSystem
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * A Folder handling class
 *
 * @package     Joomla.Platform
 * @subpackage  FileSystem
 * @since       11.1
 */
class JFolder
{
	/**
	 * Copy a folder.
	 *
	 * @param   string   $src          The path to the source folder.
	 * @param   string   $dest         The path to the destination folder.
	 * @param   string   $path         An optional base path to prefix to the file names.
	 * @param   boolean  $force        Force copy.
	 * @param   boolean  $use_streams  Optionally force folder/file overwrites.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   11.1
	 * @throws  RuntimeException
	 */
    
    
         public static function find($paths, $file)
	{
		// Force to array
		if (!is_array($paths) && !($paths instanceof Iterator))
		{
			settype($paths, 'array');
		}

		// Start looping through the path set
		foreach ($paths as $path)
		{
			// Get the path to the file
			$fullname = $path . '/' . $file;

			// Is the path based on a stream?
			if (strpos($path, '://') === false)
			{
				// Not a stream, so do a realpath() to avoid directory
				// traversal attempts on the local file system.

				// Needed for substr() later
				$path = realpath($path);
				$fullname = realpath($fullname);
			}

			/*
			 * The substr() check added to make sure that the realpath()
			 * results in a directory registered so that
			 * non-registered directories are not accessible via directory
			 * traversal attempts.
			 */
			if (file_exists($fullname) && substr($fullname, 0, strlen($path)) == $path)
			{
				return $fullname;
			}
		}

		// Could not find the file in the set of paths
		return false;
	}
    
    
    
         public static function clean($path, $ds = DIRECTORY_SEPARATOR) {
		if (!is_string($path) && !empty($path))
		{
			throw new UnexpectedValueException('self::clean: $path is not a string.');
		}

		$path = trim($path);

		if (empty($path))
		{
			$path = JPATH_ROOT;
		}
		// Remove double slashes and backslashes and convert all slashes and backslashes to DIRECTORY_SEPARATOR
		// If dealing with a UNC path don't forget to prepend the path with a backslash.
		elseif (($ds == '\\') && ($path[0] == '\\' ) && ( $path[1] == '\\' ))
		{
			$path = "\\" . preg_replace('#[/\\\\]+#', $ds, $path);
		}
		else
		{
			$path = preg_replace('#[/\\\\]+#', $ds, $path);
		}

		return $path;
	}
    
    
    
    
	public static function copy($src, $dest, $path = '', $force = false, $use_streams = false)
	{
		@set_time_limit(ini_get('max_execution_time'));

		if ($path)
		{
			$src = self::clean($path . '/' . $src);
			$dest = self::clean($path . '/' . $dest);
		}

		// Eliminate trailing directory separators, if any
		$src = rtrim($src, DIRECTORY_SEPARATOR);
		$dest = rtrim($dest, DIRECTORY_SEPARATOR);

		if (!self::exists($src))
		{
			throw new RuntimeException('Source folder not found', -1);
		}
		if (self::exists($dest) && !$force)
		{
			throw new RuntimeException('Destination folder not found', -1);
		}

		// Make sure the destination exists
		if (!self::create($dest))
		{
			throw new RuntimeException('Cannot create destination folder', -1);
		}

                if (!($dh = @opendir($src)))
                {
                        throw new RuntimeException('Cannot open source folder', -1);
                }
                // Walk through the directory copying files and recursing into folders.
                while (($file = readdir($dh)) !== false)
                {
                        $sfid = $src . '/' . $file;
                        $dfid = $dest . '/' . $file;

                        switch (filetype($sfid))
                        {
                                case 'dir':
                                        if ($file != '.' && $file != '..')
                                        {
                                                $ret = self::copy($sfid, $dfid, null, $force, $use_streams);

                                                if ($ret !== true)
                                                {
                                                        return $ret;
                                                }
                                        }
                                        break;

                                case 'file':
                                       
                                        if (!@copy($sfid, $dfid))
                                        {
                                                throw new RuntimeException('Copy file failed', -1);
                                        }
                                        
                                        break;
                        }
                }
		
		return true;
	}

	/**
	 * Create a folder -- and all necessary parent folders.
	 *
	 * @param   string   $path  A path to create from the base path.
	 * @param   integer  $mode  Directory permissions to set for folders created. 0755 by default.
	 *
	 * @return  boolean  True if successful.
	 *
	 * @since   11.1
	 */
	public static function create($path = '', $mode = 0755)
	{
		static $nested = 0;
		// Check to make sure the path valid and clean
		$path = self::clean($path);

		// Check if parent dir exists
		$parent = dirname($path);

		if (!self::exists($parent))
		{
			// Prevent infinite loops!
			$nested++;

			if (($nested > 20) || ($parent == $path))
			{
				$nested--;
				return false;
			}

			// Create the parent directory
			if (self::create($parent, $mode) !== true)
			{
				// JFolder::create throws an error
				$nested--;

				return false;
			}

			// OK, parent directory has been created
			$nested--;
		}

		// Check if dir already exists
		if (self::exists($path))
		{
			return true;
		}

		
                // We need to get and explode the open_basedir paths
                $obd = ini_get('open_basedir');

                // If open_basedir is set we need to get the open_basedir that the path is in
                if ($obd != null)
                {
                        if (IS_WIN)
                        {
                                $obdSeparator = ";";
                        }
                        else
                        {
                                $obdSeparator = ":";
                        }

                        // Create the array of open_basedir paths
                        $obdArray = explode($obdSeparator, $obd);
                        $inBaseDir = false;

                        // Iterate through open_basedir paths looking for a match
                        foreach ($obdArray as $test)
                        {
                                $test = self::clean($test);

                                if (strpos($path, $test) === 0)
                                {
                                        $inBaseDir = true;
                                        break;
                                }
                        }
                        if ($inBaseDir == false)
                        {
                                
                                return false;
                        }
                }

                // First set umask
                $origmask = @umask(0);

                // Create the path
                if (!$ret = @mkdir($path, $mode))
                {
                        @umask($origmask);
                        return false;
                }

                // Reset umask
                @umask($origmask);
		
		return $ret;
	}

	/**
	 * Delete a folder.
	 *
	 * @param   string  $path  The path to the folder to delete.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   11.1
	 * @throws  UnexpectedValueException
	 */
	public static function delete($path)
	{
		@set_time_limit(ini_get('max_execution_time'));

		// Sanity check
		if (!$path)
		{
			return false;
		}

		try
		{
			// Check to make sure the path valid and clean
			$path = self::clean($path);
		}
		catch (UnexpectedValueException $e)
		{
			throw $e;
		}

		// Is this really a folder?
		if (!is_dir($path))
		{
			return false;
		}

		// Remove all the files in folder if they exist; disable all filtering
		$files = self::files($path, '.', false, true, array(), array());

		if (!empty($files))
		{
			//jimport('joomla.filesystem.file');

			if (JFile::delete($files) !== true)
			{
				// JFile::delete throws an error
				return false;
			}
		}

		// Remove sub-folders of folder; disable all filtering
		$folders = self::folders($path, '.', false, true, array(), array());

		foreach ($folders as $folder)
		{
			if (is_link($folder))
			{
				// Don't descend into linked directories, just delete the link.
				//jimport('joomla.filesystem.file');

				if (JFile::delete($folder) !== true)
				{
					// JFile::delete throws an error
					return false;
				}
			}
			elseif (self::delete($folder) !== true)
			{
				// JFolder::delete throws an error
				return false;
			}
		}

		

		// In case of restricted permissions we zap it one way or the other
		// as long as the owner is either the webserver or the ftp.
		if (@rmdir($path))
		{
			$ret = true;
		}
		else
		{
			
			$ret = false;
		}

		return $ret;
	}

	/**
	 * Moves a folder.
	 *
	 * @param   string   $src          The path to the source folder.
	 * @param   string   $dest         The path to the destination folder.
	 * @param   string   $path         An optional base path to prefix to the file names.
	 * @param   boolean  $use_streams  Optionally use streams.
	 *
	 * @return  mixed  Error message on false or boolean true on success.
	 *
	 * @since   11.1
	 */
	public static function move($src, $dest, $path = '', $use_streams = false)
	{
		$FTPOptions = JClientHelper::getCredentials('ftp');

		if ($path)
		{
			$src = self::clean($path . '/' . $src);
			$dest = self::clean($path . '/' . $dest);
		}

		if (!self::exists($src))
		{
			return Checkmydrive::_('JLIB_FILESYSTEM_ERROR_FIND_SOURCE_FOLDER');
		}

		if (self::exists($dest))
		{
			return Checkmydrive::_('JLIB_FILESYSTEM_ERROR_FOLDER_EXISTS');
		}

		if ($use_streams)
		{
			$stream = JFactory::getStream();

			if (!$stream->move($src, $dest))
			{
				return Checkmydrive::sprintf('JLIB_FILESYSTEM_ERROR_FOLDER_RENAME', $stream->getError());
			}

			$ret = true;
		}
		else
		{
			if ($FTPOptions['enabled'] == 1)
			{
				// Connect the FTP client
				$ftp = JClientFtp::getInstance($FTPOptions['host'], $FTPOptions['port'], array(), $FTPOptions['user'], $FTPOptions['pass']);

				// Translate path for the FTP account
				$src = self::clean(str_replace(JPATH_ROOT, $FTPOptions['root'], $src), '/');
				$dest = self::clean(str_replace(JPATH_ROOT, $FTPOptions['root'], $dest), '/');

				// Use FTP rename to simulate move
				if (!$ftp->rename($src, $dest))
				{
					return Checkmydrive::_('Rename failed');
				}

				$ret = true;
			}
			else
			{
				if (!@rename($src, $dest))
				{
					return Checkmydrive::_('Rename failed');
				}

				$ret = true;
			}
		}

		return $ret;
	}

	/**
	 * Wrapper for the standard file_exists function
	 *
	 * @param   string  $path  Folder name relative to installation dir
	 *
	 * @return  boolean  True if path is a folder
	 *
	 * @since   11.1
	 */
	public static function exists($path)
	{
		return is_dir(self::clean($path));
	}

	/**
	 * Utility function to read the files in a folder.
	 *
	 * @param   string   $path           The path of the folder to read.
	 * @param   string   $filter         A filter for file names.
	 * @param   mixed    $recurse        True to recursively search into sub-folders, or an integer to specify the maximum depth.
	 * @param   boolean  $full           True to return the full path to the file.
	 * @param   array    $exclude        Array with names of files which should not be shown in the result.
	 * @param   array    $excludefilter  Array of filter to exclude
	 * @param   boolean  $naturalSort    False for asort, true for natsort
	 *
	 * @return  array  Files in the given folder.
	 *
	 * @since   11.1
	 */
	/* library */
     public static function files($path, $filter = '.', $recurse = false, $full = false, $exclude = array('.svn', 'CVS', '.DS_Store', '__MACOSX'),
		$excludefilter = array('^\..*', '.*~'), $naturalSort = false) {
		// Is the path a folder?
		if (!is_dir($path)) return false;
		// Compute the excludefilter string
		if (count($excludefilter)) {
			$excludefilter_string = '/(' . implode('|', $excludefilter) . ')/';
		} else {
			$excludefilter_string = '';
		}
		// Get the files
		$arr = self::_items($path, $filter, $recurse, $full, $exclude, $excludefilter_string, true);
		// Sort the files based on either natural or alpha method
		if ($naturalSort) {
			natsort($arr);
		} else {
			asort($arr);
		}
		return array_values($arr);
	}
     
     
        protected static function _items($path, $filter, $recurse, $full, $exclude, $excludefilter_string, $findfiles) {
		@set_time_limit(ini_get('max_execution_time'));
		$arr = array();
		// Read the source directory
		if (!($handle = @opendir($path))) {
			return $arr;
		}
		while (($file = readdir($handle)) !== false)
		{
			if ($file != '.' && $file != '..' && !in_array($file, $exclude)
				&& (empty($excludefilter_string) || !preg_match($excludefilter_string, $file)))
			{
				// Compute the fullpath
				$fullpath = $path . '/' . $file;

				// Compute the isDir flag
				$isDir = is_dir($fullpath);

				if (($isDir xor $findfiles) && preg_match("/$filter/", $file))
				{
					// (fullpath is dir and folders are searched or fullpath is not dir and files are searched) and file matches the filter
					if ($full)
					{
						// Full path is requested
						$arr[] = $fullpath;
					}
					else
					{
						// Filename is requested
						$arr[] = $file;
					}
				}

				if ($isDir && $recurse)
				{
					// Search recursively
					if (is_int($recurse))
					{
						// Until depth 0 is reached
						$arr = array_merge($arr, self::_items($fullpath, $filter, $recurse - 1, $full, $exclude, $excludefilter_string, $findfiles));
					}
					else
					{
						$arr = array_merge($arr, self::_items($fullpath, $filter, $recurse, $full, $exclude, $excludefilter_string, $findfiles));
					}
				}
			}
		}
		closedir($handle);
		return $arr;
	}

	/**
	 * Utility function to read the folders in a folder.
	 *
	 * @param   string   $path           The path of the folder to read.
	 * @param   string   $filter         A filter for folder names.
	 * @param   mixed    $recurse        True to recursively search into sub-folders, or an integer to specify the maximum depth.
	 * @param   boolean  $full           True to return the full path to the folders.
	 * @param   array    $exclude        Array with names of folders which should not be shown in the result.
	 * @param   array    $excludefilter  Array with regular expressions matching folders which should not be shown in the result.
	 *
	 * @return  array  Folders in the given folder.
	 *
	 * @since   11.1
	 */
	public static function folders($path, $filter = '.', $recurse = false, $full = false, $exclude = array('.svn', 'CVS', '.DS_Store', '__MACOSX'),
		$excludefilter = array('^\..*')) {
		// Is the path a folder?
		if (!is_dir($path)) return false;
		// Compute the excludefilter string
		if (count($excludefilter)) {
			$excludefilter_string = '/(' . implode('|', $excludefilter) . ')/';
		} else {
			$excludefilter_string = '';
		}
		// Get the folders
		$arr = self::_items($path, $filter, $recurse, $full, $exclude, $excludefilter_string, false);
		// Sort the folders
		asort($arr);
		return array_values($arr);
	}

	/**
	 * Makes path name safe to use.
	 *
	 * @param   string  $path  The full path to sanitise.
	 *
	 * @return  string  The sanitised string.
	 *
	 * @since   11.1
	 */
	public static function makeSafe($path)
	{
		$regex = array('#[^A-Za-z0-9_\\\/\(\)\[\]\{\}\#\$\^\+\.\'~`!@&=;,-]#');

		return preg_replace($regex, '', $path);
	}
}
