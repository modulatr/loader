# Modulatr Contributing Guide

Thanks for wanting to contribute to the Modulatr project! Before you do so, you might want to check out our 
CODE_OF_CONDUCT.

## Bug Reports
If you wish to report bugs, please do so using Github Issues. If you can submit a pull request, even better.

When submitting a bug, please include

 - What went wrong
 
 - The expected outcome
 
 - Steps taken to replicate the problem
 
 - The environment that the problem manifested itself (Operating system / PHP version / Framework utilised)

## Feature Requests
If you have an idea for a new feature that would benefit the project, the first step is to raise an issue and engage in
discussion with any other people who are participating in the project.

Please include in your issue:

 - What feature you are requesting
 
 - Why it would be useful (including scenarios to help the discussion)
 
 - What you are able to do currently to workaround the lack of this feature (if anything)
 
 - Any commitment you're able to make towards implementing the feature if it is accepted.
 
If your feature is accepted, it will then be added to the roadmap for future development. If you wish to, at this point 
you can create a pull request with the changes required to implement the feature.

## Security Issues

If you find any security issues in this package, please send an email to andrew@wilishq.co.uk explaining in detail what
the issue is. Please try to include:

 - The nature and impact of the issue
 
 - Steps to reproduce the issue
 
## Pull Requests

If you are submitting a pull request, please ensure that you first write a test to cover the issue before
writing any code. As a general rule, try to make your tests follow a *Given - When - Then* pattern. This is not concrete
but it does help to ensure your tests cover your issue and that other people can understand how the tests work and what
you are trying to achieve.

Also, please try to follow the naming and style convention for unit tests, ie. tests are identified by having the `@test`
annotation and the method describes what is being tested.

### Example: 
* Given `I have a loaded module`
* When `I call the getModules method`
* Then `I should see my module in the returned array`

```php

    /** @test */
    public function loadedModulesAreInTheModuleArray()
    {
        // Given
        $loader = new ModuleLoader([], [
            'modules' => [
                MyModule::class, // getId() => 'my-module'
            ],
        ]);
        
        // When
        $modules = $loader->getModules();
        
        // Then
        $this->assertArrayHasKey('my-module', $modules);
        $this->assertInstanceOf(MyModule::class, $modules['my-module']);
    }
```

If your tests pass, once a pull request has been made, it will be reviewed. Feedback will be given the request will
either be accepted, further action requested, or rejected. If a pull request is rejected, the person rejecting the 
request should issue an explanation as to why it has been rejected.
