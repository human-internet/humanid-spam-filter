# Human ID Spam Filter Contribution Guide
If you would like to contribute to this plugin:
1. Create a fork of this repository.
2. Checkout the `develop` branch. This branch will always have the latest codes.
3. The `master` branch will have the latest published codes. It will always be behind the `develop` branch
4. All pull requests should be submitted to the `develop` branch.

# Naming Convention
1. Variable names should NOT begin with underscore ( _ )
2. Variable names should be snake_case
3. Function names should be camelCase
4. Constants should be in capital letters
5. Module classes should end with Module. e.g JobModule

# Namespacing Rules
1. All PHP files should namespace humanid_spam_filter
2. Php files in the lib/ directory are exempted from this rule

# Class Rules
1. Constructor should come immediately after class variables
2. Private methods should follow the constructor
3. Protected methods should follow private methods
4. Public methods should follow protected methods