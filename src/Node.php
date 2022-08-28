<?php

declare(strict_types=1);
/**
 * happy coding.
 */
namespace Kuang\Kdis;

/**
 * 反序列化之后存在内存中的页面.
 */
class Node
{
    /**
     * 页id.
     */
    protected int $pgId;

    /**
     * 是否为叶子节点.
     */
    protected bool $isLeaf;

    /**
     * 是否已经不平衡
     */
    protected bool $unbalanced;

    /**
     * 是否已经分割.
     */
    protected bool $spilled;

    /**
     * 父节点.
     */
    protected Node $parent;

    /**
     * 子节点.
     * @var Node[]
     */
    protected array $children;

    /**
     * 键值对.
     * @var KVNode[]
     */
    protected array $kvNode;
}
