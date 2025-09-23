<?php

namespace App\Models;

// 変数定義
$var = 'the numbers are';
$var1 = $var2 = 5;
printf('s %d and %d\n', $var, $var1, $var2);
echo "{$var} {$var1} and {$var2}\n";

// 定数定義
define('FOO', 'something');
echo constant('FOO') . "\n";

// 連想配列
$member1 = [
  'name' => 'Takeru',
  'age' => 31,
];
$member2 = [
  'name' => 'Shiho',
  'age' => 29,
];

$team = [$member1, $member2];

foreach ($team as $member) {
    echo $member['name'] . "\n";
}

// クラス・オブジェクト
class Person
{
    private string $name;
    private int $age;

    public function __construct(string $name, int $age)
    {
        $this->name = $name;
        $this->name = $age;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAge(): int
    {
        return $this->age;
    }
}

$person1 = new Person('Takeru', 31);
$person2 = new Person('Shiho', 29);

$family = [$person1, $person2];

foreach ($family as $person) {
    echo $person->getName() . "\n";
}


// 関数定義
function foo_func()
{
    return 5;
}
$foo = foo_func();
echo $foo . "\n";

// 三項演算子
$var2 = $foo < 4 ? 'greet' : 'less';
echo $var2 . "\n";
?>

<?php if ($var == 'the numbers are') : ?>
this is true
<?php else : ?>
this is false
<?php endif; ?>

<?php for ($i = 0; $i < 5; $i++) : ?>
hello!
<?php endfor; ?>

<p>
    this is a p tag.
</p>
