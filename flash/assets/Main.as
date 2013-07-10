package assets {
	import flash.display.MovieClip;
	import flash.events.Event;
	
	public class Main extends MovieClip {
		public var mcCampanhas : Campanhas;
		public var mcConexoes : Conexoes;
		public var mcContexto : Contexto;
		public var mcEntrada : Entrada;

		public function Main() {
			// constructor code
			addEventListener(Event.ADDED_TO_STAGE, onAddedToStage);
		}
		
		private function onAddedToStage(e:Event) : void {
			removeEventListener(Event.ADDED_TO_STAGE, onAddedToStage);
					
			mcCampanhas.visible = false;
			mcConexoes.visible = false;
			mcContexto.visible = false;
			
			mcEntrada.addEventListener(TriboEvent.CAMPANHAS, onCampanhasClick);
			mcEntrada.addEventListener(TriboEvent.CONEXOES, onConexoesClick);
			mcEntrada.addEventListener(TriboEvent.CONTEXTO, onContextoClick);
		}
		
		private function onCampanhasClick(e:TriboEvent) : void {
			mcContexto.visible = false;
			mcConexoes.visible = false;
			mcCampanhas.visible = true;
			mcCampanhas.show();
			mcCampanhas.addEventListener(TriboEvent.CONEXOES, onConexoesClick);
			mcCampanhas.addEventListener(TriboEvent.CONTEXTO, onContextoClick);
		}
		
		private function onConexoesClick(e:TriboEvent) : void {
			mcCampanhas.visible = false;
			mcContexto.visible = false;
			mcConexoes.visible = true;
			mcConexoes.show();
			mcConexoes.addEventListener(TriboEvent.CAMPANHAS, onCampanhasClick);
			mcConexoes.addEventListener(TriboEvent.CONTEXTO, onContextoClick);
		}
		
		private function onContextoClick(e:TriboEvent) : void {
			mcCampanhas.visible = false;
			mcConexoes.visible = false;
			mcContexto.visible = true;
			mcContexto.show();
			mcContexto.addEventListener(TriboEvent.CAMPANHAS, onCampanhasClick);
			mcContexto.addEventListener(TriboEvent.CONEXOES, onConexoesClick);
		}
	}	
}