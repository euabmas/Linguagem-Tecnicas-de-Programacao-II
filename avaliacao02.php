<?php
$primeiroNome = "Ana Paula Ferreira";
$segundoNome = "Ana Luiza Moura";

$primeiroNumero = 25;
$segundoNumero = 125.55;
$terceiroNumero = 2;
$quartoNumero = 3;

echo("Primeira Avaliação LTPII - PHP: Questão 2 Desenvolva a atividade a seguir: <br>");

echo "<br>";

echo("Primeiro Nome: $primeiroNome <br>");
echo("Segundo Nome: $segundoNome <br>");

echo "<br>";

echo("Primeiro Número: $primeiroNumero <br>");
echo("Segundo Número: $segundoNumero <br>");
echo("Terceiro Número: $terceiroNumero <br>");
echo("Quarto Número: $quartoNumero <br>");

echo "<br>";

$comparacaoMaiorIgual = ($primeiroNumero >= $segundoNumero);
echo "Primeiro Número >= Segundo Número = ";
var_dump($comparacaoMaiorIgual);
echo "<br>";

$comparacaoDiferente = ($segundoNumero != $primeiroNumero);
echo "Segundo Número != Primeiro Número = ";
var_dump($comparacaoDiferente);
echo "<br>";

$raizQuadrada = sqrt($primeiroNumero);
echo "A raiz quadrada do Primeiro Número ($primeiroNumero) = " . $raizQuadrada . "<br>";

$elevadoAoCubo = pow($primeiroNumero, $terceiroNumero);
echo "O Primeiro Número ($primeiroNumero) elevado ao Terceiro Número ($terceiroNumero) = " . $elevadoAoCubo . "<br>";

$arredondamento = round($segundoNumero);
echo "Arredondando Segundo Número ($segundoNumero) = " . $arredondamento . "<br>";

$tamanhoSegundoNome = strlen($segundoNome);
echo "O Segundo Nome: $segundoNome possui $tamanhoSegundoNome caracteres<br>";

$nomeMaiusculo = strtoupper($primeiroNome);
echo "O primeiro Nome: $primeiroNome com todos caracteres em Maiúsculo = $nomeMaiusculo<br>";

$comparacaoNomes = ($primeiroNome == $segundoNome);
echo "Primeiro Nome == Segundo Nome = ";
var_dump($comparacaoNomes);
echo "<br>";

$multiplicacao = $primeiroNumero * $terceiroNumero;
echo "O Primeiro Número ($primeiroNumero) multiplicado pelo Terceiro Número ($terceiroNumero) = " . $multiplicacao . "<br>";

$restoDivisao = $multiplicacao % $quartoNumero;
echo "O resto da divisão de ($multiplicacao) pelo Quarto Número ($quartoNumero) = " . $restoDivisao . "<br>";

?>