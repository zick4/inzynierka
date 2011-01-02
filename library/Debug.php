<?php
/**
 * Klasa służąca do debugowania
 * @author pawel
 *
 */
class Debug
{
	/**
	 * Bada daną zmienną i zwraca czytelny wynik
	 * @param mixed $var
	 * @param bool $exit
	 */
    public static function dump($var, $exit=true)
    {

    	echo '<pre>';
        var_dump($var);
        if (is_object($var))
            get_class_methods($object);
    	echo '</pre>';
    	if($exit)
    		exit;

    }
	
	/**
	 * Zwraca metody danego obiektu
	 * @param mixed $object
	 * @param bool $exit
	 */
	public static function methods($object, $exit=true)
    {
		if (!is_object($object) && !is_string($object))
		{
			exit("Zmienna nie jest obiektem");
		}
    	echo '<pre>';
        get_class_methods($object);
    	echo '</pre>';
    	if($exit)
    		exit;

    }

}
?>