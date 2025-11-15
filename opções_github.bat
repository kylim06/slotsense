@echo off
cd /d %~dp0
title Gerenciador do Projeto

:menu
cls
echo ======================================
echo      GERENCIADOR DO PROJETO GIT
echo ======================================
echo.
echo 1 - Atualizar projeto (baixar versao nova)
echo 2 - Enviar minhas mudancas para o grupo
echo 3 - Sair
echo.
set /p opcao="Escolha uma opcao: "

if "%opcao%"=="1" goto pull
if "%opcao%"=="2" goto push
if "%opcao%"=="3" exit
echo Opcao invalida!
pause
goto menu

:pull
cls
echo Atualizando projeto... Aguarde.
git pull
echo.
echo ✅ Projeto atualizado com sucesso!
pause
goto menu

:push
cls
echo Preparando arquivos para enviar...
git add .

echo Escreva uma mensagem explicando o que voce mudou:
set /p msg="Mensagem: "
if "%msg%"=="" set msg=Atualizacao do projeto

echo Enviando suas mudancas... Aguarde.
git commit -m "%msg%"
git pull
git push
echo.
echo ✅ Pronto! Suas alteracoes foram enviadas para o grupo!
pause
goto menu
