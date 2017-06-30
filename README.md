# Facebook

A simple facebook generating class based on [FPDF](http://www.fpdf.org/).

## Install

Via Composer

``` bash
$ composer require xu/facebook
```

## Usage

``` php
// create a facebook instance
$pdf = new Facebook();

// set the default font
$pdf->SetFont('Times', '', 12);

// add users
$pdf->addUsers([
    User::fromArray([
        'image'      => __DIR__ . "/blue.png",
        'name'       => "lixu",
        'desc'       => "Hi, \nI am lixu.",
    ])
]);

// render it
$pdf->render();

// output to browser
$pdf->Output();
```

## Example

Please see [example](https://github.com/xu-li/facebook/blob/master/examples/index.php) for details.

## Testing

``` bash
$ phpunit
```

## Contributing

Please see [CONTRIBUTING](https://github.com/xu-li/facebook/blob/master/CONTRIBUTING.md) for details.

## Credits

- [Xu Li](https://github.com/xu-li)
- [All Contributors](https://github.com/xu-li/facebook/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
