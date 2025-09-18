<?php
$primeiroNumero = 10;
$segundoNumero = 15;
$terceiroNumero = 1200.00;

$primeiroNome = "Ana Cristina";
$segundoNome = "Gustavo Emmanuel";
$terceiroNome = "Gustavo Júnior";

echo("Primeira Avaliação LTPII - PHP: Questão 1 Desenvolva a atividade a seguir: <br>");

echo "<br>";

$mediaNumeros = ($primeiroNumero + $segundoNumero) / 2;

echo "Primeiro Número: " . $primeiroNumero . "<br>";
echo "Segundo Número: " . $segundoNumero . "<br>";

echo "Terceiro Número: " . number_format($terceiroNumero, 2, ',', '.') . "<br>";
echo "<br>";

echo "Primeiro Nome: " . $primeiroNome . "<br>";
echo "Segundo Nome: " . $segundoNome . "<br>";
echo "Terceiro Nome: " . $terceiroNome . "<br>";
echo "<br>";

echo "A média dos números (" . $primeiroNumero . " + " . $segundoNumero . ") / 2 = " . $mediaNumeros . "<br>";
echo "<br>";

$primeiroNomeMaiusculo = strtoupper($primeiroNome);
$segundoNomeMinusculo = strtolower($segundoNome);
$terceiroNomeMaiusculo = strtoupper($terceiroNome);

echo $primeiroNomeMaiusculo . " e " . $segundoNomeMinusculo . " são casados e " . $terceiroNome . " é o único filho(a) desse casal<br>";
echo $terceiroNomeMaiusculo . " é estudante de medicina e gasta semestralmente R$" . number_format($terceiroNumero * 6, 2, ',', '.') . " com o curso";

?>