Git + Node.js + NPM + Ruby no Windows
=====================================

Este tutorial mostra como instalar `Git` + `Node.js` + `NPM` + `Ruby` no Windows.

### Git

Primeiro instale: [Git for Windows](http://git-scm.com/download/win)

Quando estiver instalando, selecione as opções abaixo:

**Tela 1:**

- Git Bash Here
- Git GUI Here

**Tela 2**

- Run Git from the Windows Command Prompt

**Tela 3**

- Checkout as-is, commit Unix-style line endings

### Node.js

[Abra o link](http://nodejs.org/download/), selecione o `Windows Binary (.exe)` compatível com a sua máquina e faça o download.
   
Abra o `prompt` e digite:

    C:\mkdir node
    C:\move c:\downloaded\node.exe c:\node\.
    C:\cd c:\node
    C:\node>set path=%PATH%;%CD%
    C:\node>setx path "%PATH%"
    
O comando acima adiciona o `Node.js` no `PATH`, assim você poderá executá-lo de qualquer parte do sistema.

### NPM - Package Manager

**Atenção:** Para fazer essa parte, é importante que o `GIT` esteja funcionando corretamente.

Abra um **novo** `prompt` e digite:

    C:\cd c:\node
    C:\node>git config --system http.sslcainfo /bin/curl-ca-bundle.crt
    C:\node>git clone --recursive git://github.com/isaacs/npm.git tmp
    C:\node>node tmp\cli.js install npm
    C:\node>copy C:\node\node_modules\npm\bin\npm c:\node\.
    C:\node>copy C:\node\node_modules\npm\bin\npm.cmd c:\node\.
    C:\node>rmdir C:\node\tmp /s/q
    
### Grunt and Bower

Para instalar o **Grunt** e o **Bower** é necessário que o **Git** e o **NPM** estaja ok.  
Abra um **novo** `prompt` e digite:

    C:\>npm install -g bower
    C:\>npm install -g grunt-cli
    
### Ruby

O **Ruby** é mais simples, basta baixar e instalar: [Download Ruby](http://rubyinstaller.org/downloads/)

### Compass

Para instalar o **Compass** é necessário que o **Ruby** estaja ok.  
Abra um **novo** `prompt` e digite:

    C:\>gem --version
    C:\>gem update --system --no-ri --no-rdoc
    C:\>gem install compass --no-ri --no-rdoc
    
## Test

Verifique se está tudo **ok**.  
Abra um **novo** `prompt` e digite:

    C:\>git --version
    C:\>node --version
    C:\>npm --version
    C:\>bower --version
    C:\>grunt --version
    C:\>compass --version
