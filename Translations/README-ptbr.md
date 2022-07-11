# Princípios SOLID - explicação simples e fácil


O Princípio SOLID é um padrão de codificação que todos os desenvolvedores devem ter um conceito claro para desenvolver software de maneira adequada para evitar um design ruim. Foi promovido por Robert C Martin e é usado em todo o ambiente de design orientado a objetos. Quando aplicado corretamente, torna seu código mais extensível, lógico e fácil de ler.

Quando o desenvolvedor constrói um software seguindo o design ruim, os códigos podem se tornar inflexíveis e mais frágeis, pequenas mudanças no software podem resultar em bugs. Por essas razões, devemos seguir os Princípios SOLID.

Leva algum tempo para entender, mas se você escrever código seguindo os princípios ele melhorará a qualidade do código e ajudará a entender o software mais bem projetado

Para entender os princípios do SOLID, você precisa conhecer claramente o uso da interface.
Se o seu conceito não é claro sobre interface, então você pode ler isto 
[doc](https://medium.com/@NahidulHasan/understanding-use-of-interface-and-abstract-class-9a82f5f15837).

Vou tentar explicar os Princípios SOLID da maneira mais simples para que seja fácil
para iniciantes entenderem. Vamos passar por cada princípio um por um:


<details>
<summary> Traduções:</summary>

Original: [English]()

</details>

#

## Single Responsibility Principle (Princípio da Responsabilidade Única) :

>Uma classe deve ter um, e apenas um, motivo para mudar.

Uma classe deve servir apenas a um propósito, isso não implica que cada classe deva ter apenas um método, mas todos devem se relacionar diretamente com a responsabilidade da classe. Todos os métodos e propriedades devem trabalhar para o mesmo objetivo. Quando uma classe atende a vários propósitos ou responsabilidades, ela deve ser transformada em uma nova classe.

Por favor, veja o seguinte código :

```php
namespace Demo;
use DB;
class OrdersReport
{
    public function getOrdersInfo($startDate, $endDate)
    {
        $orders = $this->queryDBForOrders($startDate, $endDate);
        return $this->format($orders);
    }
    protected function queryDBForOrders($startDate, $endDate)
    {   // Se atualizarmos nossa camada de persistência no futuro,
        // teríamos que fazer mudanças aqui também. <=> motivo para mudar!
        return DB::table('orders')->whereBetween('created_at', [$startDate, $endDate])->get();
    }
    protected function format($orders)
    {   // Se mudarmos a forma como queremos formatar a saída,
        // teríamos que fazer alterações aqui. <=> motivo para mudar!
        return '<h1>Orders: ' . $orders . '</h1>';
    }
}
```

A classe acima viola o princípio da responsabilidade única. Por que essa classe deve recuperar dados do banco de dados? Isto está relacionado com a camada de persistência. A camada de persistência lida com a persistência (armazenamento e recuperação) de dados de um armazenamento de dados (como um banco de dados, por exemplo). Portanto, não é responsabilidade desta classe.


O formato do próximo método também não é de responsabilidade desta classe. Porque podemos precisar de dados de formatos diferentes, como XML, JSON, HTML etc.

Então, finalmente, o código refatorado será descrito abaixo :

```php
namespace Report;
use Report\Repositories\OrdersRepository;
class OrdersReport
{
    protected $repo;
    protected $formatter;
    public function __construct(OrdersRepository $repo, OrdersOutPutInterface $formatter)
    {
        $this->repo = $repo;
        $this->formatter = $formatter;
    }
    public function getOrdersInfo($startDate, $endDate)
    {
        $orders = $this->repo->getOrdersWithDate($startDate, $endDate);
        return $this->formatter->output($orders);
    }
}

namespace Report;
interface OrdersOutPutInterface
{
	public function output($orders);
}
namespace Report;
class HtmlOutput implements OrdersOutPutInterface
{
    public function output($orders)
    {
        return '<h1>Orders: ' . $orders . '</h1>';
    }
}

namespace Report\Repositories;
use DB;
class OrdersRepository
{
    public function getOrdersWithDate($startDate, $endDate)
    {
        return DB::table('orders')->whereBetween('created_at', [$startDate, $endDate])->get();
    }
}
```

## Open-closed Principle (Princípio aberto-fechado) :

>As entidades devem estar abertas para extensão, mas fechadas para modificação.

Entidades de software (classes, módulos, funções, etc.) podem ser estendidas sem realmente alterar o conteúdo da classe que você está estendendo. Se pudéssemos seguir esse princípio com força suficiente, seria possível modificar o comportamento do nosso código sem nunca tocar em um pedaço do código original.

Por favor, veja o seguinte código :

```php
class Rectangle
{
    public $width;
    public $height;
    public function __construct($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
    }
}

class Circle
{
    public $radius;
    public function __construct($radius)
    {
        $this->radius = $radius;
    }
}

class CostManager
{
    public function calculate($shape)
    {
        $costPerUnit = 1.5;
        if ($shape instanceof Rectangle) {
            $area = $shape->width * $shape->height;
        } else {
            $area = $shape->radius * $shape->radius * pi();
        }
        
        return $costPerUnit * $area;
    }
}
$circle = new Circle(5);
$rect = new Rectangle(8,5);
$obj = new CostManager();
echo $obj->calculate($circle);
```

Se quisermos calcular a área para quadrado, temos que modificar o método de cálculo na
 classe CostManager. Isso quebra o princípio aberto-fechado. De acordo com este princípio,
 não podemos modificar podemos estender. Então, como podemos corrigir esse problema, consulte o código a seguir :


```php
interface AreaInterface
{
    public  function calculateArea();
}

class Rectangle implements AreaInterface
{
    public $width;
    public $height;
    public function __construct($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
    }
    public  function calculateArea(){
        $area = $this->height *  $this->width;
        return $area;
    }
}
  
class Circle implements  AreaInterface
{
    public  $radius;
    public function __construct($radius)
    {
        $this->radius = $radius;
    }
    public  function calculateArea(){
        $area = $this->radius * $this->radius * pi();
        return $area;
    }
}

class CostManager
{
    public function calculate(AreaInterface $shape)
    {
        $costPerUnit = 1.5;
        $totalCost = $costPerUnit * $shape->calculateArea();
        return $totalCost;
    }
}
$circle = new Circle(5);
$obj = new CostManager();
echo $obj->calculate($circle);
```

Agora podemos encontrar a área do quadrado sem modificar a classe CostManager.


## Liskov Substitution Principle (Princípio da Substituição de Liskov) :

O princípio da substituição de Liskov foi introduzido por Barbara Liskov em uma conferência durante a palestra "Abstração de dados" em 1987. Barbara Liskov e Jeannette Wing formularam o princípio sucintamente em um artigo de 1994 como segue :

>Seja φ(x) uma propriedade demonstrável sobre objetos x do tipo T. Então φ(y) deve ser verdadeiro para objetos y do tipo S onde S é um subtipo de T.

A versão legível por humanos repete praticamente tudo o que Bertrand Meyer já disse, mas depende totalmente de um sistema de tipos:


>1. Pré-condições não podem ser reforçadas em um subtipo.
>2. As pós-condições não podem ser enfraquecidas em um subtipo.
>3. As invariantes do supertipo devem ser preservadas em um subtipo.

Robert Martin tornou a definição mais suave e concisa em 1996 :

>Funções que usam ponteiros de referências a classes base devem ser capazes de usar objetos de classes derivadas sem saber.

Ou simplesmente: A subclasse/classe derivada deve ser substituível por sua classe base/pai.

Ele afirma que qualquer implementação de uma abstração (interface) deve ser substituível em qualquer lugar em que a abstração seja aceita. Basicamente, ele cuida para que, ao codificar usando interfaces em nosso código, não temos apenas um contrato de entrada que a interface recebe, mas também a saída retornada por diferentes classes que implementam essa interface; elas deveriam ser do mesmo tipo.

Um trecho de código para mostrar como viola o LSP e como podemos corrigi-lo :

```php
interface LessonRepositoryInterface
{
    /**
     * Buscar todos os registros.
     *
     * @return array
     */
    public function getAll();
}

class FileLessonRepository implements LessonRepositoryInterface
{
    public function getAll()
    {
        // retornar pelo sistema de arquivos
        return [];
    }
}

class DbLessonRepository implements LessonRepositoryInterface
{
    public function getAll()
    {
        /*
            Viola a LSP porque:
               - o tipo de retorno é diferente
               - o consumidor desta subclasse e FileLessonRepository não funcionarão de forma idêntica
         */
        // retorna Lesson::all();
        // para consertar isso
        return Lesson::all()->toArray();
    }
}
```


## Interface Segregation Principle (Princípio de Segregação de Interface) :

>Um Cliente não deve ser forçado a implementar uma interface que não usa.

Esta regra significa que devemos quebrar nossas interfaces em muitas outras menores,
para que satisfaçam melhor as necessidades exatas de nossos clientes.

Semelhante ao Princípio de Responsabilidade Única, o objetivo do Princípio de Segregação de Interface é minimizar as consequências colaterais e a repetição, dividindo o software em várias partes independentes.

Vejamos um exemplo :

```php
interface workerInterface
{
    public  function work();
    public  function  sleep();
}

class HumanWorker implements workerInterface
{
    public  function work()
    {
        var_dump('works');
    }

    public  function  sleep()
    {
        var_dump('sleep');
    }

}

class RobotWorker implements workerInterface
{
    public  function work()
    {
        var_dump('works');
    }

    public  function sleep()
    {
        // Não há necessidade
    }
}
```

No código acima, RobotWorker não precisa dormir, mas a classe tem que implementar o método sleep porque sabemos que todos os métodos são abstratos na interface. Ele quebra a lei de segregação de Interface. Como podemos corrigi-lo, consulte o seguinte código :

```php
interface WorkAbleInterface
{
    public  function work();
}

interface SleepAbleInterface
{
    public  function  sleep();
}

class HumanWorker implements WorkAbleInterface, SleepAbleInterface
{
    public  function work()
    {
        var_dump('works');
    }
    public  function  sleep()
    {
        var_dump('sleep');
    }
}

class RobotWorker implements WorkAbleInterface
{
    public  function work()
    {
        var_dump('works');
    }
}
```


## Dependency Inversion Principle (Princípio da Inversão de Dependência) :

> Módulos de alto nível não devem depender de módulos de baixo nível. Ambos devem depender de abstrações.

> As abstrações não devem depender de detalhes. Os detalhes devem depender de abstrações.

Ou simplesmente : Depender de abstrações e não de concreções

Aplicando a Inversão de Dependência os módulos podem ser facilmente trocados por outros módulos apenas alterar o módulo de dependência e o módulo de alto nível não será afetado por nenhuma alteração no módulo de baixo nível.

Por favor, veja o seguinte código :

```php
class MySQLConnection
{
   /**
    * db connection
    */
    public function connect()
    {
      var_dump('MYSQL Connection');
    }
}

class PasswordReminder
{    
    /**
     * @var MySQLConnection
     */
     private $dbConnection;
     
     public function __construct(MySQLConnection $dbConnection) 
    {
      $this->dbConnection = $dbConnection;
    }
}
```

Há um mal-entendido comum de que a inversão de dependência é simplesmente outra maneira de dizer injeção de dependência. No entanto, os dois não são iguais.

No código acima, apesar de injetar a classe MySQLConnection na classe PasswordReminder, mas depende de MySQLConnection.

O módulo de alto nível PasswordReminder não deve depender do módulo de baixo nível MySQLConnection.

Se quisermos alterar a conexão de MySQL Connection para MongoDB Connection, temos que alterar a injeção do construtor codificada na classe PasswordReminder.

A classe PasswordReminder deve depender de abstrações, não de concreções. Mas como podemos fazer isso? Por favor, veja o exemplo a seguir :

```php
interface ConnectionInterface
{
   public function connect();
}

class DbConnection implements ConnectionInterface
{
   /**
    * db connection
    */
    public function connect()
    {
     var_dump('MYSQL Connection');
    }
}

class PasswordReminder
{
   /**
    * @var DBConnection
    */
    private $dbConnection;
    
    public function __construct(ConnectionInterface $dbConnection)
    {
      $this->dbConnection = $dbConnection;
    }
}
```

No código acima, queremos alterar a conexão de MySQLConnection para MongoDBConnection, não precisamos alterar a injeção de construtor na classe PasswordReminder. Porque aqui a classe PasswordReminder depende de abstrações, não de concreções.

A publicação Better Programming publicou este artigo. se você quiser ler o site do blog Better Programming, acesse este link [link](https://medium.com/better-programming/solid-principles-simple-and-easy-explanation-f57d86c47a7f).

Obrigado por ler.


### Licença

Software de código aberto licenciado sob a [MIT license](http://opensource.org/licenses/MIT)


