# Bricks Builder Adapter Documentation

The Bricks adapter performs dynamic environment checks (`defined('BRICKS_VERSION')`). When Bricks is not installed, the adapter exposes `supported = false` and operates as a capability reporting stub without throwing errors.
