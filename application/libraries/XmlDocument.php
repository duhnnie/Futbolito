<?

if (!defined('BASEPATH')) exit('No permitir el acceso directo al script');

include("Nodo.php");

class XmlDocument
{
	private $raiz=null;
	private $xml=null;
	private $content_type = "text/xml";
	private $charset = "utf-8";
	
	function __construct()
	{
	}
	
	public function crearNodo($nombre, $valor="", $padre=null)
	{
		return new Nodo($nombre,$valor, $padre);
	}
	
	public function setRaiz(Nodo $nodo)
	{
		$this->raiz=$nodo;
	}
	
	public function getRaiz()
	{
		return $this->raiz;
	}
	
	public function removeRaiz(Nodo $nodo)
	{
		$this->raiz=null;
	}
	
	public function setContentType($content)
	{
		$this->content_type = $content;
	}
	
	public function getXML()
	{
		$this->xml="";
		if(!$this->raiz)
			throw new Exception("No se puede generar el xml ya que no ha establecido un nodo raíz para el documento",12);
		$this->generarXML($this->raiz);
		return $this->xml;
	}
	
	private function generarXML(Nodo $nodo)
	{
		$this->xml.="\n<".$nodo->getNombre();
		$atributos=$nodo->getAtributos();
		$llaves=array_keys($atributos);
		
		foreach($llaves as $llave)
			$this->xml.=' '.$llave.'="'.str_replace("\"","'",$atributos[$llave]).'"';
		
		$this->xml.=">"; 
		
		if(count($nodo)>0)
			foreach($nodo as $elemento)
				$this->generarXML($elemento);
		else
			$this->xml.="<![CDATA[".$nodo->getValor()."]]>";
		
		$this->xml.="</".$nodo->getNombre().">"; 
	}
	
	public function imprimirXML()
	{
		$this->xml="";
		if(!$this->raiz)
			throw new Exception("No se puede imprimir el xml ya que no ha establecido un nodo raíz para el documento",12);
		$this->generarXML($this->raiz);
		header('Content-Type: text/xml; charset=utf-8');
		
		echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n".$this->xml;
	}

}

?>