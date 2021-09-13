<?php

class Cadastro {
    private $db;
    public function __construct(){
        $this->db = new PDO("sqlite:".__DIR__."/dados.db");
        $query = $this->db->prepare('create table if not exists cadastro(id integer primary key autoincrement, nome  text, telefone text, endereco text)');
        $query->execute();
        while(true){
            $this->main();
        }
    }
    public function main(){
        echo "Cadastro \n\n";
        echo "1 Novo Cadastro;\n";
        echo "2 Listar pessas cadastradas;\n";
        echo "3 Editar um cadastro;\n";
        echo "4 Apagar um cadastro;\n";
        echo "5 Sair \n\n";
        $opt = readline('Escolha uma opcao: ');
        if ($opt != 1 && $opt != 2 && $opt != 3 && $opt != 4 && $opt != 5  ){
            echo "Opcao invalida \n";
        } else {
            switch($opt){
                case 1 :
                    $dados = array();
                    $dados['nome'] = readline("Digite o nome: ");
                    $dados['telefone'] = readline("Digite o telefone: ");
                    $dados['endereco'] = readline("Digite o endereco: ");
                    $r = readline("Deseja salvar esses dados? [s/n] ");
                    if ($r != 's' && $r != 'n'){
                        echo "Opcao invalida \n";
                    } else {
                        if ($r == 's'){
                            $this->inserir($dados);
                        }
                    }
                    break;
                case 2 :
                    print_r($this->listar());
                    break;
                case 3 :
                    $id = readline("Digite o id do cadastro que deseja editar: ");
                    $cadastro = $this->pegar($id);
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
                            echo "Opcao invalida \n";
                        } else {
                            if ($r == 's'){
                                $this->alterar($dados);
                            }
                        }
                    } else {
                        echo "Cadastro nao localizado \n";
                    }
                    break;
                case 4 :
                    $id = readline("Digite o id do cadastro que deseja apagar: ");
                    $cadastro = $this->pegar($id);
                    if ($cadastro){
                        echo "Apagando registro: \n";
                        print_r($cadastro);
                        $r = readline("Deseja apagar esses dados? [s/n] ");
                        if ($r != 's' && $r != 'n'){
                            echo "Opcao invalida \n";
                        } else {
                            if ($r == 's'){
                                $this->apagar($id);
                            }
                        }
                    }
                    break;
                case 5 :
                    exit();
                default:
                    echo "\n\n Opcao invalida... \n";
                    echo "";
                    break;
            }
        }
    }
    public function inserir($dados){
        $query = $this->db->prepare("insert into cadastro (nome, telefone, endereco) values (:no,:te,:en)");
        $query->bindParam(':no',$dados['nome'    ],PDO::PARAM_STR);
        $query->bindParam(':te',$dados['telefone'],PDO::PARAM_STR);
        $query->bindParam(':en',$dados['endereco'],PDO::PARAM_STR);
        $query->execute();
    }
    public function alterar($dados){
        $query = $this->db->prepare("update cadastro set nome = :no, telefone = :te, endereco = :en where id = :id");
        $query->bindParam(':no',$dados['nome'    ],PDO::PARAM_STR);
        $query->bindParam(':te',$dados['telefone'],PDO::PARAM_STR);
        $query->bindParam(':en',$dados['endereco'],PDO::PARAM_STR);
        $query->bindParam(':id',$dados['id'      ],PDO::PARAM_INT);
        $query->execute();
    }
    public function listar(){
        $query = $this->db->prepare("select * from cadastro");
        $query->execute();
        $r = $query->fetchAll(PDO::FETCH_ASSOC);
        return $r;
    }
    public function apagar($id){
        $query = $this->db->prepare("delete from cadastro where id = :id");
        $query->bindParam(':id',$id,PDO::PARAM_INT);
        $query->execute();
    }
    public function pegar($id){
        $query = $this->db->prepare("select * from cadastro where id = :id");
        $query->bindParam(':id',$id,PDO::PARAM_INT);
        $query->execute();
        $r = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($r) != 0 ){
            return $r[0];
        } else {
            return false;
        }
    }
}

new Cadastro;

?>
