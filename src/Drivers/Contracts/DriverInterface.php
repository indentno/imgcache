<?php

namespace Indent\Imgcache\Drivers\Contracts;

interface DriverInterface
{
    public function base64(): string;
    public function blur(int $blur): self;
    public function brightness(int $brightness): self;
    public function crop(int $width, int $height): self;
    public function get(): string;
    public function height(int $height): self;
    public function make(string $source): self;
    public function pixelate(int $pixelate): self;
    public function width(int $width): self;
}
