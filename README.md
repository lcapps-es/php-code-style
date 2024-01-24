# LCApps ECS Configuration

## How to use this repository

1. Install the dependency: `composer req --dev lcapps/php-code-style`

2. Create ecs.php in your project root directory with the following content:

```php
    use LCApps\CodeStyle\CodeStyleSets;
    use Symplify\EasyCodingStandard\Config\ECSConfig;

    return function (ECSConfig $ecsConfig): void {
        // Configure your paths
        $ecsConfig->paths([
            __DIR__ . '/config',
            __DIR__ . '/public',
            __DIR__ . '/src',
            __DIR__ . '/tests',
        ]);

        // If you want to use Symfony rules, uncomment the following line
        //$ecsConfig->dynamicSets(['@Symfony']);
        
        $ecsConfig->sets([CodeStyleSets::DEFAULT]);
        
    };