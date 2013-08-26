<?php
/******************************************************************

Copyright (C) 2010 Andre Campos Rodovalho. All rights reserved.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>

******************************************************************/

/**
 * This class is intended to block the action of spam bots or robots (as Captcha), employing inteligent
 * tactics on human detection
 *
 * @autor Andre Campos Rodovalho - andre.rodovalho@gmail.com
 * @date 02-08-10
 * @update 05-11-10
 * @version 3.5
 * @copyright Copyright (C) 2010 Andre Campos Rodovalho. All rights reserved.
 * @license GNU/LGPL, see COPYING.LESSER
 */
class AntiBot{

	var $coresString = array( 0=>'rojo', 1=>'amarillo', 2=>'rosa', 3=>'verde', 4=>'marron',
		5=>'naranja', 6=>'azul', 7=>'gris' );
	var $coresHexa = array( 0=>'#FF0000', 1=>'#E0E000', 2=>'#FF00FF', 3=>'#339900', 4=>'#934900',
		5=>'#FB9600', 6=>'#0000FF', 7=>'#999999' );
	var $numerosString = array( 0=>'cero', 1=>'uno', 2=>'dos', 3=>'tres', 4=>'cuatro', 5=>'cinco',
		6=>'seis', 7=>'siete', 8=>'ocho', 9=>'nueve' );

	var $out = array(0=>'');
	/**
	 * Creates an instance of the class and when requested outputs an new question
	 * @param boolean $iniciarCharada Flag to output question
	 * @param boolean $charadasRandomicas Flag to make questions randomly
	 * @param int $charadaChamar When $charadasRandomicas is false, calls the corresponding method
	 */
	function AntiBot($iniciarCharada=true,$charadasRandomicas=true,$charadaChamar=0){
		@session_start();

		if( $charadasRandomicas )
			$charadaChamar = rand(0,3);

		if( $iniciarCharada ){
			switch( $charadaChamar ){
				case 0: $this->out[0]= $this->montaCharadaConta(); break;
				case 1: $this->out[0] = $this->montaCharadaContaLetra(); break;
				case 2: $this->out[0] = $this->montaCharadaCoresLetras(); break;
				case 3: $this->out[0] = $this->montaCharadaInverteTira(); break;
			}
		}
	}

	function getQuestion (){
		return $this->out;
	}

	/**
	 * Generates and random arithmetic question
	 * @param string $guardarReposta Flag to store or not the answer generated
	 * @return string $pergunta
	 */
	function montaCharadaConta($guardarReposta=true){

		$operacoesString = array(0=>'mas',1=>'menos',2=>'por');

		$indiceNumero1 = rand(0,9);
		$indiceNumero2 = rand(0,9);
		$indiceOperacao = rand(0,2);

		$resposta = 0;
		switch($indiceOperacao){
		    case 0: $resposta = $indiceNumero1 + $indiceNumero2; break;
		    case 1: $resposta = $indiceNumero1 - $indiceNumero2; break;
		    case 2: $resposta = $indiceNumero1 * $indiceNumero2; break;
		}
		if( $guardarReposta ) $this->guardaResposta($resposta);

		$variavel = 'sujeira'.rand(0,2);
		$$variavel = $this->montaSujeira();

		$pergunta = 'Que valor entero ('.$sujeira2.'considerando el signo)
			resulta de hacer'.$sujeira0.' de la operacion "'.
			$this->numerosString[$indiceNumero1].' '.$operacoesString[$indiceOperacao].' '.
			$this->numerosString[$indiceNumero2].'" ?'.$sujeira1;

		if( $guardarReposta ) { // evita chamadas recursivas
			$posicao = 'pos'.rand(0,1);
			$$posicao = '<span><div style="display:none">'.$this->montaCharadaContaLetra(false).
				'</div></span>';
		}

		return $pos1.'<span><div style="display:none">'.time().'</div>'.$pergunta.'</span>'.$pos0;
	}

	/**
	 * Stores answer on a SESSION
	 * @param string $resposta Answer to be stored
	 * @return void
	 */
	function guardaResposta($resposta){
		$_SESSION['resposta'] = $resposta;
	}

	/**
	 * Checks if sent answer and the stored answer matches
	 * @param string $resposta Sent answer (user input)
	 * @return boolean
	 */
	function verificaResposta($resposta){
		if( ($_SESSION['resposta'] == $resposta) && !empty($_SESSION['resposta']) ) return true;
		else{ $_SESSION['resposta'] = ''; return false; };
	}

	/**
	 * Generates an random question to count how many times an character occurs on a string
	 * @param string $guardarReposta Flag to store or not the answer generated
	 * @return string $pergunta
	 */
	function montaCharadaContaLetra($guardarReposta=true){

		$tamanho_palavra = 9;
		$palavra = $this->geraPalavra($tamanho_palavra);

		$indiceSorteado = rand(1,$tamanho_palavra);

		$caracterSorteado = substr( $palavra, $indiceSorteado, 1 );
		$resposta = substr_count( $palavra, $caracterSorteado );
		if( $guardarReposta ) $this->guardaResposta($resposta);

		$variavel = 'sujeira'.rand(0,2);
		$$variavel = $this->montaSujeira();

		$pergunta = 'Cuantas veces '.$sujeira0.'(valor entero) el caracter '.$sujeira1.'"'.
			$caracterSorteado.'" aparece en: '.$sujeira2.'<b>'.$palavra.'</b> ?';

		if( $guardarReposta ) {
			$posicao = 'pos'.rand(0,1);
			$$posicao = '<span><div style="display:none;">'.$this->montaCharadaConta(false).
				'</div></span>';
		}

		return $pos1.'<span><div style="display:none;">'.time().'</div>'.$pergunta.'</span>'.$pos0;
	}

	/**
	 * Generates an word (main)
	 * @param int $tamanho_palavra Desired lenght
	 * @return string $palavra
	 */
    	function geraPalavra($tamanho_palavra=5){
		$vogais = array('a','e','i','o','u');
		$consoantes = array('b','c','d','f','g','h','nh','lh','ch','j','k','l','m','n','p','qu',
			'r','rr','s','ss','t','v','w','x','y','z');

		$palavra = '';
		$contar_silabas = 0;
		while($contar_silabas < $tamanho_palavra){

			$vogal = $vogais[rand(0,count($vogais)-1)];
			$consoante = $consoantes[rand(0,count($consoantes)-1)];
			$silaba = $consoante.$vogal;
			$palavra .=$silaba;
			$contar_silabas++;
			unset($vogal,$consoante,$silaba);
		}

		return $palavra;
	}

 	/**
	 * Generates an random html to "muddy" the question, and probably cheat bots
	 * @return string $sujeira
	 */
	function montaSujeira(){

		$abreTag = array('<p style="display:none">','<span style="display:none">',
			'<tt style="display:none">','<font style="display:none">',
			'<div style="display:none">','<cite style="display:none">');

		$fechaTag = array('</p>','</span>','</tt>','</font>','</div>','</cite>');

		$indiceTags = rand(0,5);

		if( rand(0,1) )
			$sujeira = time();
		else
			$sujeira = $this->geraPalavra( rand(4,11) );

		$sujeira = $abreTag[$indiceTags].$sujeira.$fechaTag[$indiceTags];
		if( rand(0,1) ) $sujeira = str_replace('">', ';">', $sujeira);
		if( rand(0,1) ) $sujeira = str_replace('none', ' none', $sujeira);

		return $sujeira;
	}

	/**
	 * Generates an question to type the chars with an specific color from a string
	 * @param string $guardarReposta Flag to store or not the answer generated
	 * @return string $pergunta
	 */
	function montaCharadaCoresLetras($guardarReposta=true){

		$str = array();
		$tamanho = 15;
		$numeroDeColoridas = rand(1,5); //reposta
		$resposta = array();
		$indiceDaCor = rand(0,7); //sorteada
		$outrasCores = array();
		while(1){ //sorteia tres diferentes cores
			if( sizeof($outrasCores) > 2 )
				break;
			else{
				$indice = rand(0,7);
				if( $indice != $indiceDaCor )
					$outrasCores[] = $this->coresHexa[$indice];
			}
		}

		$verbete = $this->geraVerbete($tamanho);
		$verbete = str_split($verbete);

		$y = 0; // contador de quantas letras foram com a cor sorteada
		for( $i = 0; $i<$tamanho; $i++ ) { //loop que constroi um array "miolo" de charada

			$letra = $verbete[$i];

			// verifica se deve colocar cor sorteada
			if( ($y < $numeroDeColoridas) && ($numeroDeColoridas != 0) ){
				$cor = $this->coresHexa[$indiceDaCor];
				$resposta[$letra.$i] = $letra;
				$y++;
			}else{ // sortea cor sujeira
				$indice = rand(0,2);
				$cor = $outrasCores[$indice];
			}

			$str[$letra.$i] = '<font color="'.$cor.'">'.$letra.'</font> ';
		}

		ksort($resposta);
		ksort($str);
		$str = implode($str);
		$resposta = implode($resposta);
		if( $guardarReposta ) $this->guardaResposta($resposta);

		$variavel = 'sujeira'.rand(0,2);
		$$variavel = $this->montaSujeira();

		$pergunta = 'Tipee los caracteres en '.$this->coresString[$indiceDaCor].
			', considerando las mayusculas, presente'.$sujeira0.
			' en <br />( <strong>'.$str.$sujeira2.'</strong> )'.$sujeira1;

		if( $guardarReposta ) {
			$posicao = 'pos'.rand(0,1);
			$$posicao = '<span><div style="display: none">'.$this->montaCharadaInverteTira(false).
				'</div></span>';
		}

		return $pos1.'<span><div style="display: none">'.time().'</div>'.$pergunta.'</span>'.$pos0;
	}

	/**
	 * Generates an entry (main)
	 * @param int $tamanho Desired lenght
	 * @return string $verbete
	 */
	function geraVerbete($tamanho=5){

		$verbete = '';
		srand( (double)microtime()*1000000 );
		$data = "AbcDE123IJKLMN67QRSTUVWXYZ";
		$data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
		$data .= "FGH45P89";

		for( $i = 0; $i<$tamanho; $i++ ) {
			$verbete .= substr($data, (rand()%(strlen($data))), 1);
		}

		return $verbete;
	}

	/**
	 * Generates an question to type chars without an determined number, inverting or not
	 * @return string $pergunta
	 */
	function montaCharadaInverteTira($guardarReposta=true){
		$caracteres = $this->geraVerbete(5);
		$caracteres = str_split($caracteres);
		$retirarEste = 0;

		foreach($caracteres as $char){ // acha um numero na str para retirar
			$char = $char *= 1; // converte pra int
			if( $char != 0 ){
				$retirarEste = $char;
				break;
			}
		}
		if( $retirarEste == 0 ){ // se nao tiver numeros, faz um
			$retirarEste = rand(1,9); // nao coloco zero
			$caracteres[] = $retirarEste;
		}

		$caracteres = implode($caracteres);
		$resposta = str_replace($retirarEste,'',$caracteres);
		if( rand(0,1) ) {
			$resposta = strrev( $resposta );
			$inverter = '<strong>inverta</strong> toda la cadena ';
		}
		if( $guardarReposta ) $this->guardaResposta($resposta);

		$variavel = 'sujeira'.rand(0,2);
		$$variavel = $this->montaSujeira();

		$pergunta = 'Dado <font color="'.$this->coresHexa[rand(0,7)].'">'.
			$caracteres.'</font> '.$inverter.' ignorando'.$sujeira1.' el numero
			<strong>'.$this->numerosString[$retirarEste].'</strong>'.$sujeira0.
			' - para responder, considere las'.$sujeira2.' mayusculas';

		if( $guardarReposta ) {
			$posicao = 'pos'.rand(0,1);
			$$posicao = '<span><div style="display: none;">'.$this->montaCharadaCoresLetras(false).
				'</div></span>';
		}

		return $pos1.'<span><div style="display: none;">'.time().'</div>'.$pergunta.'</span>'.$pos0;
	}

}
