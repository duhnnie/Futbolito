<?

if (!defined('BASEPATH')) exit('No permitir el acceso directo al script');

class Nodo implements Countable, RecursiveIterator
{
	private $nombre;
	private $valor;
	private $atributos=array();
	private $elementos=array();
	private $actual=0;
	private $padre=null;
	
	function __construct($nombre, $valor="", $padre=null)
	{
		$this->nombre=$nombre;
		$this->valor=$valor;
		$this->padre=$padre;
	}
	
	public function getNombre()
	{
		return $this->nombre;
	}
	
	public function setValor($valor)
	{
		$this->valor=$valor;
	}
	
	public function getValor()
	{
		return $this->valor;
	}
	
	public function setPadre(Nodo $nodo){
		$this->padre=$nodo;
	}
	
	public function getPadre(){
		return $this->padre;
	}
	
	public function removePadre(){
		$this->padre=null;
	}
	
	public function addAtributo($nombre,$valor)
	{
		$this->atributos[$nombre]=$valor;
	}
	
	public function getAtributo($nombre)
	{
		return $this->atributos[$nombre];
	}
	
	public function getAtributos()
	{
		return $this->atributos;
	}
	
	public function removeAtributo($nombre)
	{
		unset($this->atributos[$nombre]);
	}
	
	public function addElemento(Nodo $nodo)
	{
		$nodo->padre=$this;
		$this->elementos[]=$nodo;
	}
	
	public function getElemento($index)
	{
		return $this->elementos[$index];
	}
	
	public function removeElemento(Nodo $nodo)
	{
		$llave=array_search($nodo, $this->elementos,true);
		
		$this->elementos[$llave]->padre=null;
		
		while(isset($this->elementos[$llave+1]))
		{
			$this->elementos[$llave]=$this->elementos[$llave+1];
			$llave++;
		}
		unset($this->elementos[$llave]);
	}
	
	//MEtodos de interfaz
	
	public function count(){
		return count($this->elementos);
	}
	
	public function getChildren()
	{
		return $this->current();
	}
	
	public function hasChildren()
	{
		if(count($this->elementos)>0)
			return true;
		else
			return false;
	}
	
	public function rewind()
	{
		$this->actual=0;	
	}
	
	public function current()
	{
		return $this->elementos[$this->actual];
	}
	
	public function key(){
		return $this->actual;
	}
	
	public function next(){
		++$this->actual;
	}
	
	public function valid(){
		return isset($this->elementos[$this->actual]);
	}
}

?>