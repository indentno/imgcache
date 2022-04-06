# Imgcache [![Build Status](https://app.travis-ci.com/s360digital/imgcache.svg?branch=master)](https://app.travis-ci.com/s360digital/imgcache)

> Cache any image from any source locally in your Laravel app

### Installation
1) Install using Composer
```bash
composer require indent/imgcache --prefer-dist
```

2) Create symbolic link between `public/` directory and `storage/imgcache/`
```bash
php artisan imgcache:link
```

3) Add your Cloudinary cloud name in `config/services.php`. You can find your cloud name in the Cloudinary dashboard.
```php
return [
    'imgcache' => [
        'cloudinary' => [
            'cloud_name' => env('CLOUDINARY_CLOUD_NAME', '<Your cloud name>'),
        ],
    ],
];
```

### Usage
Use Imgcache by calling the fascade or use the global helper.

#### Fascade
```php
class ProductController
{
    public function show()
    {
        $img = Imgcache::make('https://picsum.photos/id/1/100/100')->get();

        return view('product.show', [
            'img' => $img,
        ]);
    }
}
```

#### Helper
The global helper is useful when rendering an image in HTML or Blade views
```blade
{{-- When called in a Blade view Imgcache will be stringified --}}
<img src="{{ imgcache($imageUrl) }} alt="...">

{{-- If you want to be explicit, you can call `get()` --}}
<img src="{{ imgcache($imageUrl)->get() }} alt="...">
```

#### Inline hashes
You can generate very small image hashes to load inline in your Blade templates, then lazy load the actual image later.
```blade
<img src="{{ imgcache($imageUrl)->width(150)->blur(2000)->format('webp')->base64() }} alt="...">
```

### API

#### `base64(): string;`
Return base64 encoded image string

#### `blur(int {1, 2000} $blur): self;`
Apply blur effect to image

#### `brightness(int {-99, 100} $brightness): self;`
Apply brightness / darkness to image

#### `crop(int {50+} $width, int {50+} $height): self;`
Crops image into specified size

#### `get(): string;`
Return relative URL to image

#### `height(int {50+} $width, int|null {null|50+} $height = null): self;`
Resize image to specified height with automatic width

#### `make(string {url} $source): self;`
Create new instance of Imgcache

#### `pixelate(int {1, 200} $pixelate): self;`
Apply pixelate effect to image

#### `width(int {50+} $width, int|null {null|50+} $height = null): self;`
Resize image to specified width with automatic height

### Drivers
Currently only Cloudinary is supported through their fetch API.
