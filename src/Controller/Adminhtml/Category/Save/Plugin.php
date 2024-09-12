<?php
namespace OM\CategoryMods\Controller\Adminhtml\Category\Save;

class Plugin
{
    /**
     * @param \Magento\Catalog\Controller\Adminhtml\Category\Save $subject
     * @param $data
     * @return mixed
     */
    public function afterImagePreprocessing(\Magento\Catalog\Controller\Adminhtml\Category\Save $subject, $data)
    {
        $types = ['cat_image_small', 'cat_image_medium', 'cat_image_large'];

        foreach ($types as $type) {
            if (isset($data[$type]) && is_array($data[$type])) {
                if (!empty($data[$type]['delete'])) {
                    $data[$type] = null;
                } else {
                    if (isset($data[$type][0]['name']) && isset($data[$type][0]['tmp_name'])) {
                        $data[$type] = $data[$type][0]['name'];
                    } else {
                        unset($data[$type]);
                    }
                }
            } else {
                $data[$type] = null;
            }
        }

        return $data;
    }
}
