# ProductAttachmentStorage Module

[![Latest Stable Version](https://poser.pugx.org/spryker/product-attachment-storage/v/stable.svg)](https://packagist.org/packages/spryker/product-attachment-storage)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.3-8892BF.svg)](https://php.net/)

ProductAttachmentStorage module provides publish and subscribe functionality for product attachments, publishing attachment data to Redis for high-performance frontend access.

## Features

- Publishes product attachment data to Redis storage
- Supports locale-specific attachment storage with default fallback
- Automatic synchronization via event-driven architecture
- Client layer for efficient Redis reads with locale fallback

## Installation

```bash
composer require spryker/product-attachment-storage
```

## Documentation

[Module Documentation](https://docs.spryker.com)
