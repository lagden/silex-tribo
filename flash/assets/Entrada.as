package assets {
	import flash.display.MovieClip;
	import flash.display.SimpleButton;
	import flash.events.Event;
	import flash.events.MouseEvent;
	
	public class Entrada extends MovieClip {
		public var btnCampanhas : SimpleButton;
		public var btnConexoes : SimpleButton;
		public var btnContexto : SimpleButton;
		private var _evt : String;

		public function Entrada() {
			// constructor code
			addEventListener(Event.ADDED_TO_STAGE, onAddedToStage);
		}

		private function onAddedToStage(e:Event) : void {
			removeEventListener(Event.ADDED_TO_STAGE, onAddedToStage);
			
			addEventListener(Event.ENTER_FRAME, onPlayTimeline);
			
			btnCampanhas.visible = false;
			btnConexoes.visible = false;
			btnContexto.visible = false;
			
			/*btnCampanhas.addEventListener(MouseEvent.CLICK, onBtnClick);
			btnConexoes.addEventListener(MouseEvent.CLICK, onBtnClick);
			btnContexto.addEventListener(MouseEvent.CLICK, onBtnClick);*/
		}
		
		private function onPlayTimeline(e:Event) : void {
			if (this.currentFrame == 90) {
				btnCampanhas.visible = true;
				btnCampanhas.addEventListener(MouseEvent.CLICK, onBtnClick);
				
				btnConexoes.visible = true;
				btnConexoes.addEventListener(MouseEvent.CLICK, onBtnClick);
				
				btnContexto.visible = true;
				btnContexto.addEventListener(MouseEvent.CLICK, onBtnClick);				
			}
			
			if (this.currentFrame == 111) {
				trace('dispatch:' + this._evt);
				dispatchEvent(new TriboEvent(this._evt));
				removeEventListener(Event.ENTER_FRAME, onPlayTimeline);
			}
		}
		
		public function finaliza(evt : String) : void {
			trace('finaliza:' + evt);
			this._evt = evt;
			this.gotoAndPlay(91);
		}
		
		private function onBtnClick(e:MouseEvent) : void {
			btnCampanhas.removeEventListener(MouseEvent.CLICK, onBtnClick);
			btnConexoes.removeEventListener(MouseEvent.CLICK, onBtnClick);
			btnContexto.removeEventListener(MouseEvent.CLICK, onBtnClick);
			
			var evt : String = TriboEvent.CONTEXTO;
			if (e.currentTarget == btnCampanhas) {
				evt = TriboEvent.CAMPANHAS;
			} else if (e.currentTarget == btnConexoes) {
				evt = TriboEvent.CONEXOES;
			}
			
			finaliza(evt);
		}
	}	
}
