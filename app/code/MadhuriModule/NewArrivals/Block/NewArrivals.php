<?php

namespace MadhuriModule\NewArrivals\Block;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Catalog\Model\Product\Media\Config;
use Magento\Catalog\Model\Product\Image;
use Magento\Catalog\Helper\Image as ImageHelper;

class NewArrivals extends Template
{
    protected $productRepository;
    protected $productCollectionFactory;
    protected $timezone;
    protected $mediaConfig;
    protected $image;
    protected $imageHelper;

    public function __construct(
        Template\Context $context,
        ProductRepositoryInterface $productRepository,
        ProductCollectionFactory $productCollectionFactory,
        TimezoneInterface $timezone,
        Config $config,
        Image $img,
        ImageHelper $imghelper,
        array $data = []
    ) {
        $this->productRepository = $productRepository;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->timezone = $timezone;
        $this->mediaConfig = $config;
        $this->image = $img;
        $this->imageHelper = $imghelper;
        parent::__construct($context, $data);
    }

    public function getNewArrivals()
    {
        $newArrivals = [];
        $twoDaysAgo = $this->timezone->date(strtotime('-2 days'))->format('Y-m-d H:i:s'); 

        $productCollection = $this->productCollectionFactory->create();
        
        // Add a filter to the collection to include only products created or updated in the last two days
        $productCollection->addAttributeToSelect('*');
        $productCollection->addAttributeToFilter(
            [
                ['attribute' => 'created_at', 'gteq' => $twoDaysAgo],
                ['attribute' => 'updated_at', 'gteq' => $twoDaysAgo],
            ]
        );
        // $productCollection = $this->productCollectionFactory->create();
        // $productCollection->addAttributeToSelect('*');
        // $productCollection->addAttributeToFilter('created_at', ['gteq' => $daysAgo]);

       // $productCollection = $this->productFactory->create();
        // $productCollection->addAttributeToSelect('*');
        // $productCollection->addAttributeToFilter('created_at', ['gteq' => $daysAgo]);
        // If you want to include updated products, use the line below:
        // $productCollection->addAttributeToFilter('updated_at', ['gteq' => $daysAgo]);

        foreach ($productCollection as $product) {
            $newArrivals[] = $product;
        }
        return $newArrivals;
    }

    public function getProductImages($productId)
    {
        try {
            $product = $this->productRepository->getById($productId);
            
            // Retrieve product images
            $images = $this->getImagesFromProduct($product);

            return $images;
        } catch (\Exception $e) {
            // Handle exception if product is not found
            return [];
        }
    }

      private function getImagesFromProduct(ProductInterface $product)
    {
        $images = [];

        // Get base image
        $baseImage = $product->getMediaGalleryEntries()[0]->getFile();
        $images['base_image'] = $baseImage;

        // Get additional images
        $additionalImages = [];
        foreach ($product->getMediaGalleryEntries() as $mediaGalleryEntry) {
            if ($mediaGalleryEntry->getFile() !== $baseImage) {
                $additionalImages[] = $mediaGalleryEntry->getFile();
            }
        }
        $images['additional_images'] = $additionalImages;

        return $images;
    }

    public function getImageDynamicURL(){

        return $this->mediaConfig;
    }

    public function getMediaDirectory(){
        return $this->image;
    }

    public function getProductImageUrl($product)
    {
        // Get the image URL for the product
        $imageUrl = $this->imageHelper->init($product, 'thumbnail')
            ->setImageFile($product->getImage())
            ->getUrl();

        return $imageUrl;
    }
}