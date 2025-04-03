use Magento\Framework\App\Config\ScopeConfigInterface;

public function __construct(
    LoggerInterface $logger,
    TransportBuilder $transportBuilder,
    StoreManagerInterface $storeManager,
    ResourceConnection $resource,
    ScopeConfigInterface $scopeConfig
) {
    $this->logger = $logger;
    $this->transportBuilder = $transportBuilder;
    $this->storeManager = $storeManager;
    $this->resource = $resource;
    $this->scopeConfig = $scopeConfig;
}

protected function sendEmailNotification($order)
{
    if (!$this->scopeConfig->isSetFlag('customorderprocessing/email/enabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)) {
        return;
    }

    // Existing email sending logic...
}