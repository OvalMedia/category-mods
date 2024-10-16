<?php
declare(strict_types=1);
namespace OM\CategoryMods\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Category\Attribute\Backend\Image;

class Install implements DataPatchInterface
{
    /**
     * @var \Magento\Framework\Setup\ModuleDataSetupInterface
     */
    protected ModuleDataSetupInterface $_moduleDataSetup;

    /**
     * @var \Magento\Catalog\Setup\CategorySetupFactory
     */
    protected CategorySetupFactory $_categorySetupFactory;

    /**
     * @param \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup
     * @param \Magento\Catalog\Setup\CategorySetupFactory $categorySetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CategorySetupFactory $categorySetupFactory
    ) {
        $this->_moduleDataSetup = $moduleDataSetup;
        $this->_categorySetupFactory = $categorySetupFactory;
    }

    /**
     * @return void
     */
    public function apply(): void
    {
        $this->_installImageData();
        $this->_installShortDescription();
    }

    protected function _installShortDescription(): void
    {
        $categorySetup = $this->_categorySetupFactory->create(['setup' => $this->_moduleDataSetup]);

        try {
            $categorySetup->addAttribute(
                Category::ENTITY,
                'short_description', [
                    'type' => 'text',
                    'label' => 'Short Description',
                    'input' => 'textarea',
                    'required' => false,
                    'visible' => true,
                    'sort_order' => 1,
                    'global' => ScopedAttributeInterface::SCOPE_STORE,
                    'group' => 'General Information',
                    'is_used_in_grid' => false,
                    'is_visible_in_grid' => false,
                    'is_filterable_in_grid' => false,
                    'searchable' => true,
                    'is_html_allowed_on_front' => true,
                    'wysiwyg_enabled' => true
                ]
            );
        } catch (\Exception $e) {}
    }

    /**
     * @return void
     */
    protected function _installImageData(): void
    {
        $categorySetup = $this->_categorySetupFactory->create(['setup' => $this->_moduleDataSetup]);

        $setupData = [
            [
                'code' => 'cat_image_small',
                'label' => 'Small Image',
                'sort_order' => 10
            ],
            [
                'code' => 'cat_image_medium',
                'label' => 'Medium Image',
                'sort_order' => 20
            ],
            [
                'code' => 'cat_image_large',
                'label' => 'Large Image',
                'sort_order' => 30
            ]
        ];

        try {
            foreach ($setupData as $item) {
                $categorySetup->addAttribute(
                    Category::ENTITY,
                    $item['code'], [
                        'type' => 'varchar',
                        'label' => $item['label'],
                        'input' => 'image',
                        'backend' => Image::class,
                        'required' => false,
                        'sort_order' => $item['sort_order'],
                        'global' => ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'Images',
                        'is_used_in_grid' => true,
                        'is_visible_in_grid' => true,
                        'is_filterable_in_grid' => false
                    ]
                );
            }
        } catch (\Exception $e) {}
    }


    /**
     * @return array|string[]
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @return array|string[]
     */
    public function getAliases(): array
    {
        return [];
    }
}