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
    	echo '</pre>';
        if (is_object($var))
        {
            $var = get_class_methods($var);
            sort($var);
            var_dump($var);
        }
    	if($exit)
        {
    		exit;
        }
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
            throw new Exception("Zmienna nie jest obiektem");
		}
        $object = get_class_methods($object);
        sort($object);
        var_dump($object);
        if($exit)
        {
    		exit;
        }

    }

}
?>