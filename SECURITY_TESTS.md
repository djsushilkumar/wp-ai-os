# WP AI OS — Security Test Suite & Automated Assertions

## Automated Test Coverage

```
tests/Unit/AI/KeyEncryptorTest.php
   ├── testEncryptionAndDecryption
   └── testRejectsWeakSecretSalt (Asserts LogicException when AUTH_KEY is default string)

tests/Unit/Abilities/AbilityFrameworkTest.php
   ├── testAbilityExecution
   └── testAbilityDiscoverySyncToMcp

tests/Unit/Elementor/ElementorBuilderTest.php
   ├── testContainerBuilderProducesValidNode
   ├── testSectionBuilderProducesValidNode
   ├── testPageBuilderFromDefinition
   ├── testValidatorAcceptsValidElement
   └── testValidatorRejectsMissingId

tests/Unit/Automation/WorkflowEngineTest.php
   ├── testSuccessfulWorkflowExecution
   └── testDependencyPlannerOrdering
```

---

## Running Test Suite

```bash
composer test
```
