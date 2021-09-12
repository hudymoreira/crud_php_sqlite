<?php
#by hudymoreira@gmail.com
$db = new SQLite3('dados.db');
$db->query('create table if not exists cadastro(id integer primary key autoincrement, nome  text, telefone text, endereco text)');

function inserir($dados){
    $GLOBALS['db']->query("insert into cadastro (nome, telefone, endereco) values ('".
    $dados['nome']."','".$dados['telefone']."','".$dados['endereco']."')");
}

function listar(){
    $query = $GLOBALS['db']->query("select * from cadastro");
    while($dados = $query->fetchArray(SQLITE3_ASSOC)){
        print_r($dados);
    }
}

function alterar($dados){
    $GLOBALS['db']->query("update cadastro set nome = '".$dados['nome']."', telefone = '".
    $dados['telefone']."',endereco = '".$dados['endereco']."' where id = '".$dados['id']."' ");
}

function apagar($id){
    $GLOBALS['db']->query("delete from cadastro where id = ".$id);
}
function pegar($id){
    $query = $GLOBALS['db']->query("select * from cadastro where id = " .$id);
    $r = array();
    while($dados = $query->fetchArray(SQLITE3_ASSOC)){
        $r[] = $dados;;
    }
    if (count($r) != 0 ){
        return $r[0];
    } else {
        return false;
    }
}
function main(){
    echo "Cadastro \n\n";
    echo "1 Novo Cadastro;\n";
    echo "2 Listar pessas cadastradas;\n";
    echo "3 Editar um cadastro;\n";
    echo "4 Apagar um cadastro;\n";
    echo "5 Sair \n\n";
    $opt = readline('Escolha uma opção: ');
    if ($opt != 1 && $opt != 2 && $opt != 3 && $opt != 4 && $opt != 5  ){
        echo "Opção invalida \n";
    } else {
        switch($opt){
            case 1 :
                $dados = array();
                $dados['nome'] = readline("Digite o nome: ");
                $dados['telefone'] = readline("Digite o telefone: ");
                $dados['endereco'] = readline("Digite o endereco: ");
                $r = readline("Deseja salvar esses dados? [s/n] ");
                if ($r != 's' && $r != 'n'){
                    echo "Opção invalida \n";
                } else {
                    if ($r == 's'){
                        inserir($dados);
                    }
                }
                break;
            case 2 :
                print_r(listar());
                break;
            case 3 :
                $id = readline("Digite o id do cadastro que deseja editar: ");
                $cadastro = pegar($id);
                if ($cadastro){
                    $dados = array();
                    $dados['nome'] = readline("Digite o nome: ");
                    $dados['telefone'] = readline("Digite o telefone: ");
                    $dados['endereco'] = readline("Digite o endereco: ");
                    echo "antes: \n";
                    print_r($cadastro);
                    echo "depois: \n";
                    print_r($dados);
                    $dados['id'] = $id;
                    $r = readline("Deseja editar esses dados? [s/n] ");
                    if ($r != 's' && $r != 'n'){
                        echo "Opção invalida \n";
                    } else {
                        if ($r == 's'){
                            alterar($dados);
                        }
                    }
                } else {
                    echo "Cadastro não localizado \n";
                }
                break;
            case 4 :
                $id = readline("Digite o id do cadastro que deseja apagar: ");
                $cadastro = pegar($id);
                if ($cadastro){
                    echo "Apagando registro: \n";
                    print_r($cadastro);
                    $r = readline("Deseja apagar esses dados? [s/n] ");
                    if ($r != 's' && $r != 'n'){
                        echo "Opção invalida \n";
                    } else {
                        if ($r == 's'){
                            apagar($id);
                        }
                    }
                }
                break;
            case 5 :
                exit();
            default:
                echo "\n\n Opção invalida... \n";
                break;
        }
    }
}
while(true){
    main();
}

?>
