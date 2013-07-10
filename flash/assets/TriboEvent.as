package assets 
{
	import flash.events.Event;
	
	/**
	 * ...
	 * @author Celina Uemura (cezinha@cezinha.com.br)
	 */
	public class TriboEvent extends Event 
	{
		//  PUBLIC PROPERTIES -------------------------------------------------------------------------------------	
		public static const CAMPANHAS : String = "campanhas";
		public static const CONEXOES : String = "conexoes";
		public static const CONTEXTO : String = "contexto";
		
		//  PRIVATE PROPERTIES ------------------------------------------------------------------------------------
		
		//  CONSTRUCTOR -------------------------------------------------------------------------------------------
		public function TriboEvent(type:String, bubbles:Boolean = false, cancelable:Boolean = false) {
			super(type, bubbles, cancelable);
		}
		
		//  EVENT HANDLERS ----------------------------------------------------------------------------------------			
		//  GETTERS & SETTERS METHODS -----------------------------------------------------------------------------
	}
}