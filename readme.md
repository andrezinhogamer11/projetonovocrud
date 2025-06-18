# minha informações para atividade

NOME: Mario Gonçalves de Freitas Junior
DATA: 10/06/2025

![alt text](<Captura de tela 2025-06-10 165712.png>) ![alt text](<Captura de tela 2025-06-10 165515.png>) ![alt text](<Captura de tela 2025-06-10 164447.png>) ![alt text](<Captura de tela 2025-06-10 164119.png>)

# **WSL**
Permite rodar distribuições linux dentro do windows.

O comando abaixo instala o Ubuntu como distribuição **wsl**.

```bash
wsl --install -d Ubuntu
```

No powershel, na primeira vez que executar o comando `wsl`, 
vai ser pedido para escolher um nome de usuário, digitar a senha e digitar 
a senha novamente.

> Obs: ao digitar a senha **nunca**, será mostrado os caracteres.

# Pós instalação 

Na pós instalação **deve-se atualizar o sistema operacional** com os comandos

```bash
sudo apt update
sudo apt upgrade
```

# Install o **mariadb** como banco de dados.

**Antes de instalar qualquer programa**, sempre validar se a lista de 
programas está atualizada

```bash
sudo apt update
```

**Instalar** o **mariadb**:
```bash
sudo apt install mariadb-server
```

## Pós instalação do mariadb
Roda o comando após a instalação:
```bash
sudo mysql_secure_installation
```


**Enter current password for root (enter for none)**: # **enter**

**Switch to unix_socket authentication** [Y/n]: # **n**

**Change the root password?** [Y/n]: # **y**

**New password**: # **123456**

**Re-enter new password**: # **123456**

**Remove anonymous users?** [Y/n]: # **y**

**Disallow root login remotely?** [Y/n]: # **n**

**Remove test database and access to it?** [Y/n]: # **y**

**Reload privilege tables now?** [Y/n]: # **y**

# Como gerenciar o serviço de banco de dados

``` bash
sudo systemctl start mariadb # inicia
sudo systemctl stop mariadb # para
sudo systemctl restart mariadb # restarda
sudo systemctl status mariadb # mostras o estado
```

# Comandos MariaDB

**Acessar** com: 
``` bash
mysql -uroot -p
```


Para **mostrar** os **bancos de dados**
``` bash
MariaDB [(none)]> show databases;
```

Para **criar** o **banco de dados**
``` bash
MariaDB [(none)]> create database 'nome do banco';
```

Para **entrar ou trocar** no **banco de dados**
``` bash
MariaDB [(none)]> use 'nome do banco sem aspas simples!';
MariaDB [('banco de exemplo')]>
``` 

### Criar as colunas

```sql
--- Tabela usuário
create table usuario (
    id int not null primary key auto_increment,
    nome varchar(100) not null,
    login varchar (50) not null unique,
    senha varchar (255) not null,
    email varchar(255) not null unique,
    foto_path varchar(255) null
);

--- Tabela de Tarefa 
create table tarefa (
    id int not null primary key auto_increment,
    titulo varchar(255) not null,
    descricao text not null unique,
    status TINYINT(1) NOT NULL,
    user_id INT NOT NULL,
    CONSTRAINT fk_usuario_tarefa FOREIGN KEY (user_id) REFERENCES usuario(id) -- LAÇO DE LIGAÇÃO 
    ON DELETE CASCADE ON UPDATE CASCADE
);


-- Ver estrutura das TABELAS
describe 'tabela sem aspas';

--- Pegar os dados
select * from 'tabela sem aspas';
```