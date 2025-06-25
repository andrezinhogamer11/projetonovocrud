<!-- login.php -->
 <?php require_once('../components/header.php'); ?>
<div id="login/">
 <form>
    <div>
        <label>Login</label>
        <input type="text" name="login">
    </div>
    <div>
        <label>Senha</label>
        <input type="password" name="senha">
        </div>
        <div>  
        <button type="button">Entrar</button>
        <a href="/cadastrar">Cadastrar</a>
        <a href="/esqueci-minha-senha">Esqueci minha senha</a>    
        </div>
 </form>
</div>
<?php require_once('../components/footer.php'); ?>