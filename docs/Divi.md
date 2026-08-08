# Divi Builder Adapter Documentation

The Divi adapter performs dynamic environment checks (`defined('ET_BUILDER_VERSION')`). When Divi is not installed, the adapter exposes `supported = false` and operates as a capability reporting stub without inventing unverified APIs.
